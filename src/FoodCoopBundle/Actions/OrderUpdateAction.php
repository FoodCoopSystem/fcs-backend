<?php

namespace FoodCoopBundle\Actions;

use FoodCoopBundle\Entity\Order;
use Codifico\Component\Actions\Action\Basic\UpdateAction;
use FoodCoopBundle\Event\EntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;

class OrderUpdateAction extends UpdateAction
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, FormFactoryInterface $formFactory, $type)
    {
        $this->dispatcher = $dispatcher;
        parent::__construct($formFactory, $type);
    }
    /**
     * @param Order $order
     * @return OrderUpdateAction
     */
    public function setOrder(Order $order)
    {
        parent::setObject($order);

        return $this;
    }

    /**
     * @param $object
     * @return void
     */
    public function postUpdate($object)
    {
        $this->dispatcher->dispatch('action.update', new EntityUpdatedEvent($object));
    }
}
