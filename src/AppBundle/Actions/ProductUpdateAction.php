<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Product;
use Codifico\Component\Actions\Action\UpdateAction;
use AppBundle\Event\EntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ProductUpdateAction extends UpdateAction
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, FormFactoryInterface $formFactory, $type)
    {
        $this->dispatcher = $dispatcher;
        parent::__construct($formFactory, $type);
    }
    /**
     * @param Product $product
     * @return ProductUpdateAction
     */
    public function setProduct(Product $product)
    {
        parent::setObject($product);

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
