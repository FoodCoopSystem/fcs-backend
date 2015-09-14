<?php

namespace AppBundle\Controller;

use AppBundle\Actions\ProductCreateAction;
use AppBundle\Actions\ProductRemoveAction;
use AppBundle\Actions\ProductUpdateAction;
use AppBundle\Entity\Product;
use AppBundle\Request\Criteria;
use Codifico\Component\Actions\Action\IndexAction;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/product", service="controller.product")
 */
class ProductController
{
    use RestTrait;

    private $create;
    private $update;
    private $index;
    private $remove;

    public function __construct(ProductCreateAction $create, ProductUpdateAction $update, IndexAction $index, ProductRemoveAction $remove)
    {
        $this->create = $create;
        $this->update = $update;
        $this->index = $index;
        $this->remove = $remove;
    }

    /**
     * @Route("", name="product_create")
     * @Method({"POST"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return \Symfony\Component\Form\FormInterface
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
     * @Route("/{id}", name="product_edit")
     * @Method({"POST"})
     * @Secure(roles="ROLE_ADMIN")
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
     * @Route("", name="product_list")
     * @param Criteria $criteria
     *
     * @return \FOS\RestBundle\View\View
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
     * @Route("/{id}", name="product_remove")
     * @Method({"DELETE"})
     * @Secure(roles="ROLE_ADMIN")
     * @param Product $product
     */
    public function removeAction(Product $product)
    {
        $this->remove->setProduct($product)->execute();
    }

    /**
     * @Route("/{id}", name="product_view")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     *
     * @param Product $product
     * @return \FOS\RestBundle\View\View
     */
    public function viewAction(Product $product)
    {
        return $this->renderRestView($product, Codes::HTTP_OK, [], ['product_view']);
    }
}
