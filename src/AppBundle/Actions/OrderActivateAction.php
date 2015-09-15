<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderRepository;
use Codifico\Component\Actions\Action\ActionInterface;

class OrderActivateAction implements ActionInterface
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