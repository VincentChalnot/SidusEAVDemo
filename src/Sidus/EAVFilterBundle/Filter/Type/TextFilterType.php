<?php

namespace Sidus\EAVFilterBundle\Filter\Type;

use Elastica\Query;
use Sidus\EAVFilterBundle\Filter\ElasticaFilterInterface;
use Sidus\FilterBundle\Filter\FilterInterface;
use Sidus\FilterBundle\Filter\Type\TextFilterType as BaseTextFilterType;
use Symfony\Component\Form\FormInterface;

class TextFilterType extends BaseTextFilterType implements ElasticaFilterTypeInterface
{

    /**
     * @param ElasticaFilterInterface|FilterInterface $filter
     * @param FormInterface $form
     * @param Query\Bool $query
     */
    public function handleESForm(ElasticaFilterInterface $filter, FormInterface $form, Query\Bool $query)
    {
        $data = $form->getData();
        if (!$data) {
            return;
        }
        foreach ($filter->getAttributes() as $column) {
            $subQuery = new Query\Match($column, $data);
            $query->addMust($subQuery);
        }
    }
}
