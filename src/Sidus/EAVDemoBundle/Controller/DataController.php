<?php

namespace Sidus\EAVDemoBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sidus\EAVDemoBundle\Entity\Data;
use Sidus\EAVFilterBundle\Configuration\FilterConfigurationHandler;
use Sidus\EAVModelBundle\Model\FamilyInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * @param string $familyCode
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function listAction($familyCode, Request $request)
    {
        $family = $this->getFamily($familyCode);
        $filterConfigName = 'sidus_eav_filter.configuration.' . $familyCode;
        $isDefault = false;
        if (!$this->has($filterConfigName)) {
            throw new \UnexpectedValueException("Missing filter configuration for family {$family->getCode()}");
        }
        /** @var FilterConfigurationHandler $filterConfig */
        $filterConfig = $this->get($filterConfigName);

        if ($isDefault) {
            $filterConfig->addSortable($family->getAttributeAsLabel()->getCode());
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

    /**
     * @Template()
     * @param string $familyCode
     * @param Request $request
     * @return Response
     */
    public function createAction($familyCode, Request $request)
    {
        $family = $this->getFamily($familyCode);
        $class = $this->container->getParameter('sidus_eav_model.entity.data.class');
        return $this->editAction($familyCode, new $class($family), $request);
    }

    /**
     * @Template()
     * @param string $familyCode
     * @param Data $data
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function editAction($familyCode, Data $data, Request $request)
    {
        $family = $this->getFamily($familyCode);
        if ($family->getCode() !== $data->getFamilyCode()) {
            throw new \UnexpectedValueException("Data family '{$data->getFamilyCode()}'' not matching admin family {$familyCode}");
        }
        $data->setValueClass($this->container->getParameter('sidus_eav_model.entity.value.class'));
        $form = $this->createForm('sidus_data', $data);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getManager();
            $em->persist($data);
            $em->flush();

            $this->addFlashMsg('success', 'sidus.admin.success');
            return $this->redirectToRoute('sidus_eavdemo.data.edit', [
                'familyCode' => $family->getCode(),
                'id' => $data->getId(),
            ]);
        }

        return [
            'family' => $family,
            'form' => $form->createView(),
            'data' => $data,
        ];
    }

    /**
     * @param string $familyCode
     * @return FamilyInterface
     */
    protected function getFamily($familyCode)
    {
        return $this->get('sidus_eav_model.family_configuration.handler')->getFamily($familyCode);
    }

    /**
     * Get the current session or create a new one
     * @return Session $session
     */
    protected function getSession()
    {
        $session = $this->get('session');
        if (!$session) {
            $session = new Session();
            $session->start();
        }

        return $session;
    }

    /**
     * Add a new flash message
     * @param string $name
     * @param mixed  $value
     */
    protected function addFlashMsg($name, $value)
    {
        $this->getSession()->getFlashBag()->add($name, $value);
    }

    /**
     * Alias to get a doctrine repository
     * @param  string           $persistentObjectName
     * @param  string           $persistentManagerName
     * @return EntityRepository
     */
    protected function getRepository($persistentObjectName, $persistentManagerName = null)
    {
        return $this->getManager($persistentManagerName)->getRepository($persistentObjectName);
    }

    /**
     * Alias to return the entity manager
     * @param null $persistentManagerName
     * @return EntityManager
     */
    protected function getManager($persistentManagerName = null)
    {
        return $this->getDoctrine()->getManager($persistentManagerName);
    }
}