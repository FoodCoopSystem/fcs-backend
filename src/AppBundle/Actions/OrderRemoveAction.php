<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Order;
use Codifico\Component\Actions\Action\RemoveAction;
use AppBundle\Event\EntityRemoveEvent;
use Codifico\Component\Actions\Repository\ActionRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderRemoveAction extends RemoveAction
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EventDispatcherInterface $dispatcher, ActionRepositoryInterface $repository, LoggerInterface $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;

        parent::__construct($repository);
    }

    /**
     * @param $object
     * @return void
     */
    public function postRemove($object)
    {
        $this->dispatcher->dispatch('action.remove', new EntityRemoveEvent($object));
    }

    /**
     * @param Order $order
     * @return OrderRemoveAction
     */
    public function setOrder(Order $order)
    {
        parent::setObject($order);

        return $this;
    }

    /**
     * Creates new entity
     *
     * @return mixed
     */
    public function execute()
    {
        if ($this->object->isActive()) {
            throw new \LogicException('Order is active');
        }

        if ($this->object->hasItems()) {
            throw new \LogicException('Order has items');
        }

        $message = 'Deleting order: '. $this->object->getExecutionAt()->format('Y-m-d').'.';
        foreach ($this->object->getItems() as $item) {
            $message .= ' Item: User.id='.$item->getOwner()->getId()
                .' Product.id='.$item->getProduct()->getId().' quantity='.$item->getQuantity().'.';
        }

        $this->logger->info($message);

        parent::execute();

        $this->postRemove($this->object);
    }
}
