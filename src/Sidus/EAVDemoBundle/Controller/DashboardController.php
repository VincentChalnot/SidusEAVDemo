<?php

namespace Sidus\EAVDemoBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     * @return array
     */
    public function dashboardAction()
    {
        return [];
    }
}