<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Product;
use Codifico\Component\Actions\Action\RemoveAction;

class ProductRemoveAction extends RemoveAction
{
    public function __construct()
    {
    }

    /**
     * @param $object
     * @return void
     */
    public function dispatchEvent($object)
    {
    }

    public function setProduct(Product $product)
    {
        parent::setObject($product);
    }

    /**
     * Creates new entity
     *
     * @return mixed
     */
    public function __invoke()
    {
        $this->object->inactivate();
    }
}
