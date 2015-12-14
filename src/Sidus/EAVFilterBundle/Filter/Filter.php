<?php

namespace Sidus\EAVFilterBundle\Filter;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sidus\EAVModelBundle\Model\AttributeInterface;
use Sidus\EAVModelBundle\Model\FamilyInterface;
use Sidus\FilterBundle\Filter\Filter as BaseFilter;
use Symfony\Component\Form\FormInterface;

class Filter extends BaseFilter
{
    /** @var AttributeInterface[] */
    protected $attributeJoins = [];

    /**
     * @param FormInterface $form
     * @param QueryBuilder $qb
     * @param string $alias
     */
    public function handleForm(FormInterface $form, QueryBuilder $qb, $alias)
    {
        $this->getFilterType()->handleForm($this, $form, $qb, $alias);
        foreach ($this->attributeJoins as $customAlias => $attribute) {
            $qb->leftJoin($alias . '.values', $customAlias, Join::WITH, "({$customAlias}.attributeCode = '{$attribute->getCode()}')");
        }
    }

    /**
     * @param string $alias
     * @return array
     */
    public function getFullAttributeReferences($alias)
    {
        $references = [];
        foreach ($this->getAttributes() as $attribute) {
            if (false === strpos($attribute, '.')) {
                if (!empty($this->options['family']) && $this->options['family'] instanceof FamilyInterface) {
                    /** @var FamilyInterface $family */
                    $family = $this->options['family'];
                    if ($family->hasAttribute($attribute)) {
                        $customAlias = uniqid('join');
                        $references[] = $customAlias . '.' . $family->getAttribute($attribute)->getType()->getDatabaseType();
                        $this->attributeJoins[$customAlias] = $family->getAttribute($attribute);
                        continue;
                    }
                }
                $references[] = $alias . '.' . $attribute;
            } else {
                $references[] = $attribute;
            }
        }
        return $references;
    }
}
