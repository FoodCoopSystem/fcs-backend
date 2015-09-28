<?php

namespace AppBundle\Actions;


use AppBundle\Entity\OrderItemRepository;
use AppBundle\Entity\OrderRepository;
use Codifico\Component\Actions\Action\Basic\IndexAction;
use Codifico\Component\Actions\Request\Criteria;

class OrderIndexCurrentAction extends IndexAction
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository)
    {
        $this->orderRepository = $orderRepository;
        parent::__construct($orderItemRepository);
    }

    /**
     * @param Criteria $criteria
     *
     * @return IndexAction
     */
    public function setCriteria(Criteria $criteria)
    {
        $order = $this->orderRepository->findActive();
        $filters = $criteria->getFilters();
        $filters['order'] = $order;
        $criteria = new Criteria($filters, $criteria->getCount(), $criteria->getPage(), $criteria->getOrderBy());

        return parent::setCriteria($criteria);
    }
}
