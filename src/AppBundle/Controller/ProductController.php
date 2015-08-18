<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Producent;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRepository;
use AppBundle\Form\ProductType;
use AppBundle\Request\Criteria;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
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
     * @var EntityManager
     */
    private $entityManager;
    private $formFactory;

    public function __construct(ProductRepository $repository, EntityManager $entityManager, FormFactoryInterface $formFactory)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
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
        return $this->handleForm($request);
    }

    /**
     * @Route("/{id}", name="product_edit")
     * @Method({"POST"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editAction(Request $request, $id)
    {
        /** @var Product $product */
        $product = $this->repository->find($id);

        if (!$product) {
            throw new NotFoundHttpException(sprintf('Product %s does not exists', $id));
        }

        return $this->handleForm($request, $product);
    }


    /**
     * @Route("", name="product_list")
     * @ParamConverter("queryCriteria", converter="query_criteria_converter")
     * @param Criteria $criteria
     *
     * @return \FOS\RestBundle\View\View
     */
    public function indexAction(Criteria $criteria)
    {
        $data = [
            'total' => $this->repository->countByCriteria($criteria),
            'result' => $this->repository->findByCriteria($criteria),
        ];

        return $this->renderRestView($data, Codes::HTTP_OK, [], ['product_index']);
    }

    /**
     * @param Request $request
     * @param null $product
     * @return \Symfony\Component\Form\FormInterface
     * @internal param $product
     */
    private function handleForm(Request $request, $product = null)
    {
        $form = $this->formFactory->createNamed('', new ProductType(), $product);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $product = $form->getData();

            $this->entityManager->persist($product);
            $this->entityManager->flush($product);

            return $this->renderRestView($product, Codes::HTTP_CREATED, [], ['product_create']);
        }

        return $form;
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

        $product->inactivate();

        $this->entityManager->persist($product);
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
