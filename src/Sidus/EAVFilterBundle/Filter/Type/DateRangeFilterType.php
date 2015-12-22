<?php

namespace Sidus\EAVFilterBundle\Filter\Type;

use Elastica\Query;
use Sidus\EAVFilterBundle\Filter\ElasticaFilterInterface;
use Sidus\FilterBundle\Filter\Type\DateRangeFilterType as BaseDateRangeFilterType;
use Symfony\Component\Form\FormInterface;

class DateRangeFilterType extends BaseDateRangeFilterType implements ElasticaFilterTypeInterface
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
