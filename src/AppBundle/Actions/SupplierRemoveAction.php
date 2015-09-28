<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Supplier;
use Codifico\Component\Actions\Action\Basic\RemoveAction;
use AppBundle\Event\EntityRemoveEvent;
use Codifico\Component\Actions\Repository\ActionRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SupplierRemoveAction extends RemoveAction
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, ActionRepository $repository)
    {
        $this->dispatcher = $dispatcher;
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
     * @param Supplier $supplier
     * @return SupplierRemoveAction
     */
    public function setSupplier(Supplier $supplier)
    {
        parent::setObject($supplier);

        return $this;
    }

    /**
     * Creates new entity
     *
     * @return mixed
     */
    public function execute()
    {
        $this->object->inactivate();

        $this->postRemove($this->object);
    }
}
