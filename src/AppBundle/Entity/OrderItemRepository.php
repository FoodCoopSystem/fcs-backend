<?php

namespace AppBundle\Entity;


use Codifico\Component\Actions\Repository\ActionRepositoryInterface;
use Codifico\Component\Actions\Request\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class OrderItemRepository extends CriteriaAwareRepository implements ActionRepositoryInterface
{
    public function findNearest()
    {
        $criteria = new Criteria(['active' => true], null, null, null);

        return $this->findOneByCriteria($criteria);
    }
}
