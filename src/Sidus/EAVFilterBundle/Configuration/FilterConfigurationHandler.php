<?php

namespace Sidus\EAVFilterBundle\Configuration;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sidus\EAVModelBundle\Configuration\FamilyConfigurationHandler;
use Sidus\EAVModelBundle\Model\FamilyInterface;
use Sidus\FilterBundle\Configuration\FilterConfigurationHandler as BaseFilterConfigurationHandler;
use Sidus\FilterBundle\DTO\SortConfig;
use Sidus\FilterBundle\Filter\FilterFactory;
use UnexpectedValueException;

class FilterConfigurationHandler extends BaseFilterConfigurationHandler
{
    /** @var FamilyInterface */
    protected $family;

    /** @var string */
    protected $valueAlias;

    /**
     * @param string $code
     * @param Registry $doctrine
     * @param FilterFactory $filterFactory
     * @param array $configuration
     * @param FamilyConfigurationHandler $familyConfigurationHandler
     */
    public function __construct($code, Registry $doctrine, FilterFactory $filterFactory, array $configuration = [], FamilyConfigurationHandler $familyConfigurationHandler)
    {
        if (!$familyConfigurationHandler->hasFamily($code)) {
            throw new UnexpectedValueException("Filter configuration must match the family code, '{$code}' given");
        }
        $this->family = $familyConfigurationHandler->getFamily($code);
        parent::__construct($code, $doctrine, $filterFactory, $configuration);
    }

    /**
     * @param string $alias
     * @return QueryBuilder
     */
    public function getQueryBuilder($alias = 'e')
    {
        if (!$this->queryBuilder) {
            $this->alias = $alias;
            $this->queryBuilder = $this->repository->createQueryBuilder($alias);
            $this->queryBuilder
                ->addSelect('value')
                ->leftJoin($alias . '.values', 'value') // Manual join on values
                ->andWhere("{$alias}.familyCode IN (:families)")
                ->setParameter('families', $this->family->getMatchingCodes());
        }
        return $this->queryBuilder;
    }

    /**
     * @param array $configuration
     * @throws UnexpectedValueException
     */
    protected function parseConfiguration(array $configuration)
    {
        $this->entityReference = $configuration['entity'];
        $this->repository = $this->doctrine->getRepository($this->entityReference);
        foreach ($configuration['fields'] as $code => $field) {
            $field['options']['family'] = $this->family;
            $this->addFilter($this->filterFactory->create($code, $field));
        }
        $this->sortable = $configuration['sortable'];
        $this->sortConfig = new SortConfig();
    }

    /**
     * @param QueryBuilder $qb
     * @throws \Exception
     */
    protected function applySort(QueryBuilder $qb)
    {
        $sortConfig = $this->applySortForm();
        $column = $sortConfig->getColumn();

        if ($column) {
            $fullColumnReference = $column;
            if (false === strpos($column, '.')) {
                $fullColumnReference = $this->alias . '.' . $column;
            }
            if ($this->family->hasAttribute($column)) {
                $attribute = $this->family->getAttribute($column);
                $uid = uniqid('join');
                $fullColumnReference = $uid . '.' . $attribute->getType()->getDatabaseType();
                $qb->leftJoin($this->alias . '.values', $uid, Join::WITH,
                    "({$uid}.data = {$this->alias}.id AND ({$uid}.attributeCode = '{$attribute->getCode()}' OR {$uid}.id IS NULL))");
            }
            $direction = $sortConfig->getDirection() ? 'DESC' : 'ASC'; // null or false both default to ASC
            $qb->addOrderBy($fullColumnReference, $direction);
        }
    }
}