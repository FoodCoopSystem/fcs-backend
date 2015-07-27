<?php

namespace AppBundle\Request;

/**
 * Criteria data object
 */
class Criteria
{
    /**
     * @var mixed
     */
    private $filters;

    /**
     * @var integer
     */
    private $count;

    /**
     * @var integer
     */
    private $page;

    /**
     * @var mixed
     */
    private $orderBy;

    /**
     * @param $filters
     * @param $count
     * @param $page
     * @param $orderBy
     */
    public function __construct($filters, $count = null, $page = null, $orderBy = null)
    {
        $this->filters = $filters;
        $this->count = $count;
        $this->page = $page;
        $this->orderBy = $orderBy;
    }

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return mixed
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }
}
