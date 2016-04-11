<?php

namespace Demo\AdminBundle\Controller;

use Sidus\AdminBundle\Controller\AdminInjectable;
use Sidus\DataGridBundle\Model\DataGrid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Demo\Component\Controller\AdminControllerTrait;
use Demo\Component\Controller\BaseControllerTrait;

abstract class BaseAdminController extends Controller implements AdminInjectable
{
    use BaseControllerTrait;
    use AdminControllerTrait;

    /**
     * @return string
     */
    protected function getDataGridConfigCode()
    {
        return $this->admin->getCode();
    }

    /**
     * @return DataGrid
     * @throws \UnexpectedValueException
     */
    protected function getDataGrid()
    {
        return $this->get('sidus_data_grid.datagrid_configuration.handler')
            ->getDataGrid($this->getDataGridConfigCode());
    }

    protected function bindDataGridRequest(DataGrid $dataGrid, Request $request)
    {
        // Create form with filters
        $builder = $this->createFormBuilder(null, [
            'method' => $request->getMethod(),
            'csrf_protection' => false,
            'action' => $this->getCurrentUri($request),
        ]);
        $dataGrid->buildForm($builder);
        $dataGrid->handleRequest($request);
    }
}