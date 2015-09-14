<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Product;
use Codifico\Component\Actions\Action\RemoveAction;
use AppBundle\Event\EntityRemoveEvent;
use Codifico\Component\Actions\Repository\ActionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProductRemoveAction extends RemoveAction
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, ActionRepositoryInterface $repository)
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
