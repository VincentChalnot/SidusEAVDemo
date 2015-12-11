<?php

namespace Sidus\EAVFilterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SidusEAVFilterBundle extends Bundle
{
    public function getParent()
    {
        return 'SidusFilterBundle';
    }
}
