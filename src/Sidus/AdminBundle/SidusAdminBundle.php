<?php

namespace Sidus\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SidusAdminBundle extends Bundle
{
    public function getParent()
    {
        return 'SonataAdminBundle';
    }
}
