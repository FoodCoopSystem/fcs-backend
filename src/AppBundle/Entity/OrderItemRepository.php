<?php

namespace AppBundle\Entity;


use Codifico\Component\Actions\Repository\ActionRepositoryInterface;
use Codifico\Component\Actions\Request\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class OrderItemRepository extends EntityRepository implements ActionRepositoryInterface
{
    public function findByCriteria(Criteria $criteria)
    {
        $builder = $this->createQueryBuilder('t');
        $this->applyFilters($builder, $criteria);

        $orderBy = $criteria->getOrderBy();
        if (null !== $orderBy) {
            foreach ($orderBy as $field => $order) {
                $builder->addOrderBy('t.' . $field, $order);
            }
        }

        $builder->setMaxResults($criteria->getCount());
        $offset = ($criteria->getPage() - 1) * $criteria->getCount();
        $builder->setFirstResult($offset);

        $query = $builder->getQuery();

        return $query->execute();
    }

    public function countByCriteria(Criteria $criteria)
    {
        $builder = $this->createQueryBuilder('t');
        $this->applyFilters($builder, $criteria);
        $builder->select('count(t.id)');

        $query = $builder->getQuery();
        return $query->getSingleScalarResult();
    }

    public function add($entity)
    {
        $this->getEntityManager()->persist($entity);
    }

    private function applyFilters(QueryBuilder $builder, Criteria $criteria)
    {
        foreach ($criteria->getFilters() as $field => $value) {
            $builder->andWhere('t.'.$field.' = :'.$field);
            $builder->setParameter($field, $value);
        }
    }

    public function findNearest()
    {
        $criteria = new Criteria(['active' => true], null, null, null);

        return $this->findOneByCriteria($criteria);
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

    /**
     * Creates new instance of object
     *
     * @return mixed
     */
    public function create()
    {
        throw new \InvalidArgumentException("You should not call Repository::create");
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
}
