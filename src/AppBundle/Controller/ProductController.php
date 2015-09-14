<?php

namespace AppBundle\Controller;

use AppBundle\Actions\ProductCreateAction;
use AppBundle\Actions\ProductRemoveAction;
use AppBundle\Actions\ProductUpdateAction;
use AppBundle\Entity\Product;
use AppBundle\Request\Criteria;
use Codifico\Component\Actions\Action\IndexAction;
use FOS\RestBundle\Util\Codes;

class ProductController
{
    use RestTrait;

    private $create;
    private $update;
    private $index;
    private $remove;

    /**
     * @param ProductCreateAction $create
     * @param ProductUpdateAction $update
     * @param IndexAction $index
     * @param ProductRemoveAction $remove
     */
    public function __construct(ProductCreateAction $create, ProductUpdateAction $update, IndexAction $index, ProductRemoveAction $remove)
    {
        $this->create = $create;
        $this->update = $update;
        $this->index = $index;
        $this->remove = $remove;
    }

    /**
     * @return \FOS\RestBundle\View\View|null
     */
    public function createAction()
    {
        return $this->renderRestView(
            $this->create->execute(),
            Codes::HTTP_CREATED,
            [],
            ['product_create']
        );
    }

    /**
     * @param Product $product
     * @return \FOS\RestBundle\View\View|null
     */
    public function editAction(Product $product)
    {
        return $this->renderRestView(
            $this->update->setProduct($product)->execute(),
            Codes::HTTP_OK,
            [],
            ['product_update']
        );
    }

    /**
     * @param Criteria $criteria
     * @return \FOS\RestBundle\View\View|null
     */
    public function indexAction(Criteria $criteria)
    {
        return $this->renderRestView(
            $this->index->setCriteria($criteria)->execute(),
            Codes::HTTP_OK,
            [],
            ['product_index']
        );
    }

    /**
     * @param Product $product
     */
    public function removeAction(Product $product)
    {
        $this->remove->setProduct($product)->execute();
    }

    /**
     * @param Product $product
     * @return \FOS\RestBundle\View\View|null
     */
    public function viewAction(Product $product)
    {
        return $this->renderRestView($product, Codes::HTTP_OK, [], ['product_view']);
    }
}
