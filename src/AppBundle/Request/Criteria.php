<?php

namespace AppBundle\Request;
use Codifico\Component\Actions\Request\Criteria as BaseCriteria;

/**
 * Criteria data object
 */
class Criteria extends BaseCriteria
{
    /**
     * @param $filters
     * @param $count
     * @param $page
     * @param $orderBy
     */
    public function __construct($filters, $count = null, $page = null, $orderBy = null)
    {
        parent::__construct($filters, $count, $page, $orderBy);
    }

}
