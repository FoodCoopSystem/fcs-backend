<?php

namespace AppBundle\Entity;


use Codifico\Component\Actions\Repository\ActionRepository;
use Codifico\Component\Actions\Request\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class OrderItemRepository extends CriteriaAwareRepository implements ActionRepository
{
    public function findNearest()
    {
        $criteria = new Criteria(['active' => true], null, null, null);

        return $this->findOneByCriteria($criteria);
    }
}
