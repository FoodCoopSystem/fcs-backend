<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Codifico\Component\Actions\Request\Criteria;
use Doctrine\ORM\QueryBuilder;

abstract class CriteriaAwareRepository extends EntityRepository
{
    public function findByCriteria(Criteria $criteria)
    {
        $builder = $this->createQueryBuilder('t');

        $orderBy = $criteria->getOrderBy();
        if (null !== $orderBy) {
            foreach ($orderBy as $field => $order) {
                $builder->addOrderBy('t.' . $field, $order);
            }
        }

        $builder->setMaxResults($criteria->getCount());
        $offset = ($criteria->getPage() - 1) * $criteria->getCount();
        $builder->setFirstResult($offset);

        $this->applyFilters($builder, $criteria);
        $query = $builder->getQuery();

        return $query->execute();
    }

    public function countByCriteria(Criteria $criteria)
    {
        $builder = $this->createQueryBuilder('t');
        $builder->select('count(t.id)');

        $this->applyFilters($builder, $criteria);
        $query = $builder->getQuery();
        return $query->getSingleScalarResult();
    }

    private function applyFilters(QueryBuilder $builder, Criteria $criteria)
    {
        foreach ($criteria->getFilters() as $field => $value) {
            $builder->andWhere('t.'.$field.' = :'.$field);
            $builder->setParameter($field, $value);
        }
    }

    public function add($entity)
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * Removes entity from the repository
     *
     * @param $entity
     * @return void
     */
    public function remove($entity)
    {
        $this->getEntityManager()->remove($entity);
    }

    public function findOneByCriteria(Criteria $criteria)
    {
        /** @var QueryBuilder $builder */
        $builder = $this->createQueryBuilder('t');
        $this->applyFilters($builder, $criteria);

        $query = $builder->getQuery();

        $results = $query->execute();
        if ($results) {
            return $results[0];
        }
    }
}