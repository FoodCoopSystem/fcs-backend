<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Codifico\Component\Actions\Repository\ActionRepositoryInterface;
use Codifico\Component\Actions\Request\Criteria;

class SupplierRepository extends EntityRepository implements ActionRepositoryInterface
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
        $builder->andWhere('t.deletedAt IS NULL');

        $query = $builder->getQuery();

        return $query->execute();
    }

    public function countByCriteria(Criteria $criteria)
    {
        $builder = $this->createQueryBuilder('t');
        $builder->select('count(t.id)');
        $builder->andWhere('t.deletedAt IS NULL');

        $query = $builder->getQuery();
        return $query->getSingleScalarResult();
    }

    public function add($entity)
    {
        $this->getEntityManager()->persist($entity);
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
