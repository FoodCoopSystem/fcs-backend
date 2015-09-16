<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Supplier;
use Codifico\Component\Actions\Action\UpdateAction;
use AppBundle\Event\EntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;

class SupplierUpdateAction extends UpdateAction
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, FormFactoryInterface $formFactory, $type)
    {
        $this->dispatcher = $dispatcher;
        parent::__construct($formFactory, $type);
    }
    /**
     * @param Supplier $supplier
     * @return SupplierUpdateAction
     */
    public function setSupplier(Supplier $supplier)
    {
        parent::setObject($supplier);

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
