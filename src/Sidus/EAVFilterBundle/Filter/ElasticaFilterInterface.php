<?php

namespace Sidus\EAVFilterBundle\Filter;

use Elastica\Query;
use Symfony\Component\Form\FormInterface;

interface ElasticaFilterInterface
{
    /**
     * @param FormInterface $form
     * @param Query $query
     * @param string $alias
     */
    public function handleESForm(FormInterface $form, Query $query, $alias);
}
