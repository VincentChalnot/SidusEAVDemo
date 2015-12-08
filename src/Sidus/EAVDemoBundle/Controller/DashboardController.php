<?php

namespace Sidus\EAVDemoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
     * @Template()
     * @return array
     */
    public function dashboardAction()
    {
        return [];
    }
}