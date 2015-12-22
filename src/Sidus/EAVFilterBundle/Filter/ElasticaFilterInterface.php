<?php

namespace Sidus\EAVFilterBundle\Filter;

use Elastica\Query;
use Symfony\Component\Form\FormInterface;

interface ElasticaFilterInterface
{
    /**
     * @param FormInterface $form
     * @param Query\Bool $query
     */
    public function handleESForm(FormInterface $form, Query\Bool $query);
}
