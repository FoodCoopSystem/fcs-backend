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
     * @var ProductRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var F
     */
    private $formFactory;

    private $create;

    public function __construct(ProductCreateAction $create, ProductRepository $repository, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->create = $create;
    }

    /**
     * @Route("", name="product_create")
     * @Method({"POST"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAction(Request $request)
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
     */
    public function editAction(Request $request, Product $product)
    {
        $action = new ProductUpdateAction($this->formFactory, new ProductType($this->entityManager));
        $action->setRequest($request);
        $action->setObject($product);

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
        $action = new IndexAction($this->repository);
        $action->setCriteria($criteria);

        return $this->renderRestView($action(), Codes::HTTP_OK, [], ['product_index']);
    }

    /**
     * @Route("/{id}", name="product_remove")
     * @Method({"DELETE"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @param $id
     */
    public function removeAction($id)
    {
        /** @var Product $product */
        $product = $this->repository->find($id);

        if (!$product) {
            throw new NotFoundHttpException(sprintf('Product %s does not exists', $id));
        }

        $action = new ProductRemoveAction();
        $action->setProduct($product);
        $action();

        $this->entityManager->flush();
    }

    /**
     * @Route("/{id}", name="product_view")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @param $id
     *
     * @return \FOS\RestBundle\View\View
     */
    public function viewAction($id)
    {
        /** @var Product $product */
        $product = $this->repository->find($id);

        if (!$product) {
            throw new NotFoundHttpException(sprintf('Product %s does not exists', $id));
        }

        return $this->renderRestView($product, Codes::HTTP_OK, [], ['product_view']);
    }
}
