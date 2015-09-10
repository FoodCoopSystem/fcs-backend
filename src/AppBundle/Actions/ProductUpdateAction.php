<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Product;
use Codifico\Component\Actions\Action\UpdateAction;

class ProductUpdateAction extends UpdateAction
{
    /**
     * @param $object
     * @return void
     */
    public function dispatchEvent($object)
    {
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

    public function setObject($entity)
    {
        $this->setProduct($entity);
    }
}
