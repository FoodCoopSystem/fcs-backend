<?php

namespace AppBundle\Controller;

use AppBundle\Actions\ProductCreateAction;
use AppBundle\Actions\ProductRemoveAction;
use AppBundle\Actions\ProductUpdateAction;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRepository;
use AppBundle\Form\ProductType;
use AppBundle\Request\Criteria;
use Codifico\Component\Actions\Action\IndexAction;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/product", service="controller.product")
 */
class ProductController
{
    use RestTrait;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $create;
    private $update;
    private $index;
    private $remove;

    public function __construct(ProductCreateAction $create, ProductUpdateAction $update, IndexAction $index, ProductRemoveAction $remove, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $action = $this->create;
        $result = $action();

        $this->entityManager->flush();

        return $this->renderRestView($result, Codes::HTTP_CREATED, [], ['product_create']);
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
        $action = $this->update->setProduct($product);
        $result = $action();

        $this->entityManager->flush();

        return $this->renderRestView($result, Codes::HTTP_OK, [], ['product_update']);

    }

    /**
     * @Route("", name="product_list")
     * @param Criteria $criteria
     *
     * @return \FOS\RestBundle\View\View
     */
    public function indexAction(Criteria $criteria)
    {
        $action = $this->index->setCriteria($criteria);

        return $this->renderRestView($action(), Codes::HTTP_OK, [], ['product_index']);
    }

    /**
     * @Route("/{id}", name="product_remove")
     * @Method({"DELETE"})
     * @Secure(roles="ROLE_ADMIN")
     * @param Product $product
     */
    public function removeAction(Product $product)
    {
        $action = $this->remove->setProduct($product);
        $action();

        $this->entityManager->flush();
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
