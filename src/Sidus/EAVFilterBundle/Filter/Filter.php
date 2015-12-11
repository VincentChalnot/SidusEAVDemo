<?php

namespace Sidus\EAVFilterBundle\Filter;

use Sidus\FilterBundle\Filter\Filter as BaseFilter;

class Filter extends BaseFilter
{
    /**
     * @param string $alias
     * @return array
     */
    public function getFullAttributeReferences($alias)
    {
        $references = [];
        foreach ($this->getAttributes() as $attribute) {
            if (false === strpos($attribute, '.')) {
                $references[] = $alias . '.' . $attribute;
            } else {
                $references[] = $attribute;
            }
        }
        return $references;
    }
}
