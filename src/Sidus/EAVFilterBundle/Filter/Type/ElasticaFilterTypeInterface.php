<?php

namespace Sidus\EAVFilterBundle\Filter\Type;

use Elastica\Query;
use Sidus\EAVFilterBundle\Filter\ElasticaFilterInterface;
use Symfony\Component\Form\FormInterface;

interface ElasticaFilterTypeInterface
{
    /**
     * @param ElasticaFilterInterface $filter
     * @param FormInterface $form
     * @param Query $query
     * @param string $alias
     * @return
     */
    public function handleESForm(ElasticaFilterInterface $filter, FormInterface $form, Query $query, $alias);
}
