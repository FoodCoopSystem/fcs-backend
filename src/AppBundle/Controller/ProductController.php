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
<<<<<<< HEAD
     * @Secure(roles="ROLE_ADMIN")
=======
     * @Route("", name="product_create")
     * @Method({"POST"})
>>>>>>> 920e676dd142e4e5b49e639e86b943f06552a844
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
<<<<<<< HEAD
     * @Secure(roles="ROLE_ADMIN")
     * @param Product $product
     * @return \FOS\RestBundle\View\View|null
=======
     * @Route("/{id}", name="product_edit")
     * @Method({"POST"})
>>>>>>> 920e676dd142e4e5b49e639e86b943f06552a844
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
<<<<<<< HEAD
     * @Secure(roles="ROLE_ADMIN")
     * @param Product $product
=======
     * @Route("/{id}", name="product_remove")
     * @Method({"DELETE"})
     *
     * @param $id
>>>>>>> 920e676dd142e4e5b49e639e86b943f06552a844
     */
    public function removeAction(Product $product)
    {
        $this->remove->setProduct($product)->execute();
    }

    /**
<<<<<<< HEAD
     * @Secure(roles="ROLE_ADMIN")
=======
     * @Route("/{id}", name="product_view")
     * @Method({"GET"})
>>>>>>> 920e676dd142e4e5b49e639e86b943f06552a844
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
