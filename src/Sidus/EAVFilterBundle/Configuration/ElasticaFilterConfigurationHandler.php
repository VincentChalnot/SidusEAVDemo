<?php

namespace Sidus\EAVFilterBundle\Configuration;

use Elastica\Query;
use Sidus\EAVFilterBundle\Filter\ElasticaFilterInterface;
use Symfony\Component\HttpFoundation\Request;

class ElasticaFilterConfigurationHandler extends FilterConfigurationHandler
{
    /** @var Query */
    protected $esQuery;

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function handleRequest(Request $request)
    {
        $this->getForm()->handleRequest($request);
        $this->applyESFilters($this->getESQuery()); // @todo maybe do it in a form event ?
        $this->applyESSort($this->getESQuery());
    }

    /**
     * @return Query
     */
    public function getESQuery()
    {
        if (!$this->esQuery) {
            $this->esQuery = new Query();
        }
        return $this->esQuery;
    }

    /**
     * @param $query
     * @return ElasticaFilterConfigurationHandler
     */
    public function setESQuery($query)
    {
        $this->esQuery = $query;
        return $this;
    }

    /**
     * @param Query $query
     * @throws \Exception
     */
    protected function applyESFilters(Query $query)
    {
        $form = $this->getForm();
        $filterForm = $form->get(self::FILTERS_FORM_NAME);
        foreach ($this->getFilters() as $filter) {
            if (!$filter instanceof ElasticaFilterInterface) {
                throw new \Exception("Unsupported filter type for elastic search"); // @todo refactor with better exception
            }
            $filter->handleESForm($filterForm->get($filter->getCode()), $query, $this->alias);
        }
    }

    /**
     * @param Query $query
     * @throws \Exception
     */
    protected function applyESSort(Query $query)
    {
        $sortConfig = $this->applySortForm();

        $column = $sortConfig->getColumn();
        if ($column) {
            $direction = $sortConfig->getDirection() ? 'DESC' : 'ASC'; // null or false both default to ASC
            $query->addSort([
                $column => [
                    'order' => $direction,
                ]
            ]);
        }
    }
}