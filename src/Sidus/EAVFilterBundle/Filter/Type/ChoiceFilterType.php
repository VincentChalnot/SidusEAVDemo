<?php

namespace Sidus\EAVFilterBundle\Filter\Type;

use Elastica\Query;
use Sidus\EAVFilterBundle\Filter\ElasticaFilterInterface;
use Sidus\FilterBundle\Filter\Type\ChoiceFilterType as BaseChoiceFilterType;
use Symfony\Component\Form\FormInterface;

class ChoiceFilterType extends BaseChoiceFilterType implements ElasticaFilterTypeInterface
{

    /**
     * @param ElasticaFilterInterface $filter
     * @param FormInterface $form
     * @param Query\Bool $query
     */
    public function handleESForm(ElasticaFilterInterface $filter, FormInterface $form, Query\Bool $query)
    {
        // TODO: Implement handleESForm() method.
    }
}
