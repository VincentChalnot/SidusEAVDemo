<?php

namespace Demo\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sidus\AdminBundle\Admin\Action;
use Sidus\EAVDataGridBundle\Model\DataGrid;
use Sidus\EAVModelBundle\Model\FamilyInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Demo\Component\Controller\DataControllerTrait;
use Demo\EAVModelBundle\Entity\Data;

class DataController extends BaseAdminController
{
    use DataControllerTrait;

    protected function getDataGridConfigCode()
    {
        if ($this->get('sidus_data_grid.datagrid_configuration.handler')->hasDataGrid($this->family->getCode())) {
            return $this->family->getCode();
        }
        return 'data';
    }

    /**
     * @inheritdoc
     */
    protected function getDataGrid()
    {
        $datagrid = parent::getDataGrid();
        if ($datagrid instanceof DataGrid) {
            $datagrid->setFamily($this->family);
        }
        return $datagrid;
    }

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
     * @param FamilyInterface $family
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function listAction(FamilyInterface $family, Request $request)
    {
        $this->family = $family;
        $dataGrid = $this->getDataGrid();
        $dataGrid->setActionParameters('create', [
            'familyCode' => $family->getCode(),
        ]);

        $this->bindDataGridRequest($dataGrid, $request);

        return [
            'datagrid' => $dataGrid,
            'family' => $family,
        ];
    }

    /**
     * @Template()
     * @param FamilyInterface $family
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function createAction(FamilyInterface $family, Request $request)
    {
        /** @var Data $data */
        $data = $family->createData();
        return $this->editAction($family, $data, $request);
    }

    /**
     * @Template()
     * @param FamilyInterface $family
     * @param Data $data
     * @param Request $request
     * @return array|RedirectResponse
     * @throws \Exception
     */
    public function editAction(FamilyInterface $family, Data $data, Request $request)
    {
        $this->initDataFamily($family, $data);

        $form = $this->getForm($request, $data);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->saveEntity($data);

            return $this->redirectToEntity($data, 'edit');
        }

        return $this->renderAction($this->getViewParameters($request, $form, $data));
    }

    /**
     * @Template()
     * @param FamilyInterface $family
     * @param Data $data
     * @param Request $request
     * @return array|RedirectResponse
     * @throws \Exception
     */
    public function deleteAction(FamilyInterface $family, Data $data, Request $request)
    {
        $this->initDataFamily($family, $data);

        $builder = $this->createFormBuilder(null, $this->getDefaultFormOptions($request, $data->getId()));
        $form = $builder->getForm();
        $dataId = $data->getId();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->deleteEntity($data);

            return $this->redirectToAdmin($this->admin, 'list', [
                'familyCode' => $family->getCode(),
            ]);
        }

        return $this->renderAction($this->getViewParameters($request, $form, $data) + [
            'dataId' => $dataId,
        ]);
    }

    /**
     * @param Request $request
     * @param string $dataId
     * @param Action|null $action
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function getDefaultFormOptions(Request $request, $dataId, Action $action = null)
    {
        if (!$action) {
            $action = $this->admin->getCurrentAction();
        }
        $options = parent::getDefaultFormOptions($request, $dataId, $action);
        $options['label'] = "admin.family.{$this->family->getCode()}.{$action->getCode()}.title";
        return $options;
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param Data $data
     * @return array
     */
    protected function getViewParameters(Request $request, Form $form, $data)
    {
        return parent::getViewParameters($request, $form, $data) + [
            'family' => $data->getFamily(),
        ];
    }
}
