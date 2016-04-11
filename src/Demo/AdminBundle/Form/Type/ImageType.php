<?php

namespace Demo\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class ImageType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'demo_image';
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return 'sidus_resource';
    }
}
