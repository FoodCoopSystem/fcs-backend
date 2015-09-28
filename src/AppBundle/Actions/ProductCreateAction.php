<?php

namespace AppBundle\Actions;

use Codifico\Component\Actions\Action\Basic\CreateAction;
use AppBundle\Event\EntityCreatedEvent;
use Codifico\Component\Actions\Repository\ActionRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ProductCreateAction extends CreateAction
{
    private $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ActionRepository  $repository,
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
