<?php

namespace FoodCoopBundle\Actions;

use FoodCoopBundle\Entity\Product;
use Codifico\Component\Actions\Action\Basic\RemoveAction;
use FoodCoopBundle\Event\EntityRemoveEvent;
use Codifico\Component\Actions\Repository\ActionRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProductRemoveAction extends RemoveAction
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
     * @param Product $product
     * @return ProductRemoveAction
     */
    public function setProduct(Product $product)
    {
        parent::setObject($product);

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
