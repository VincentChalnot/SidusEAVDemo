<?php

namespace Sidus\EAVDemoBundle\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sidus\EAVDemoBundle\Entity\Data;
use Sidus\FilterBundle\Configuration\FilterConfigurationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DataController extends Controller
{
    /**
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Template()
     * @param $familyCode
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function listAction($familyCode, Request $request)
    {
        $family = $this->get('sidus_eav_model.family_configuration.handler')->getFamily($familyCode);
        $filterConfigName = 'sidus_filter.configuration.' . $familyCode;
        $isDefault = false;
        if (!$this->has($filterConfigName)) {
            $filterConfigName = 'sidus_filter.configuration.default';
            $isDefault = true;
        }
        /** @var FilterConfigurationHandler $filterConfig */
        $filterConfig = $this->get($filterConfigName);

        if ($isDefault) {
            $filterConfig->addSortable('value.' . $family->getAttributeAsLabel()->getCode());
        }

        $qb = $filterConfig->getQueryBuilder();
        $alias = $filterConfig->getAlias();
        $qb
            ->addSelect('value')
            ->leftJoin($alias . '.values', 'value') // Manual join on user
            ->andWhere("{$alias}.familyCode IN (:families)")
            ->setParameter('families', $family->getMatchingCodes());

        // Create form with filters
        $builder = $this->createFormBuilder(null, [
            'method' => 'get',
            'csrf_protection' => false,
        ]);
        $form = $filterConfig->buildForm($builder);
        $filterConfig->handleRequest($request);

        $adapter = new DoctrineORMAdapter($qb);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(50);
        $pager->setCurrentPage($request->get('page', 1));

        /** @var Data[] $logs */
        $datas = $pager->getCurrentPageResults();

        return [
            'family' => $family,
            'form' => $form->createView(),
            'datas' => $datas,
            'pager' => $pager,
        ];
    }
}