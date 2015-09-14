<?php

namespace AppBundle\Actions;

use Codifico\Component\Actions\Action\CreateAction;
use AppBundle\Event\EntityCreatedEvent;
use Codifico\Component\Actions\Repository\ActionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ProductCreateAction extends CreateAction
{
    private $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ActionRepositoryInterface  $repository,
        FormFactoryInterface  $formFactory,
        $type)
    {
        $this->dispatcher = $dispatcher;
        parent::__construct($repository, $formFactory, $type);
    }

    /**
     * @param $object
     * @return void
     */
    public function postCreate($object)
    {
        $this->dispatcher->dispatch('action.create', new EntityCreatedEvent($object));
    }
}
