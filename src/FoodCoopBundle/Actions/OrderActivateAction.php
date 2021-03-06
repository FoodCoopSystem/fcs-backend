<?php

namespace FoodCoopBundle\Actions;

use FoodCoopBundle\Entity\Order;
use FoodCoopBundle\Entity\OrderRepository;
use Codifico\Component\Actions\Action\Action;

class OrderActivateAction implements Action
{
    /**
     * @var OrderRepository
     */
    private $repository;

    /**
     * @var Order
     */
    private $order;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Order $order
     *
     * @return OrderActivateAction
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Execute given actions
     *
     * @return mixed
     */
    public function execute()
    {
        $this->repository->activate($this->order);
    }
}
