<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Producent;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRepository;
use AppBundle\Form\ProductType;
use AppBundle\Request\Criteria;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function __construct(ProductRepository $repository, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
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
        $code = $product ? Codes::HTTP_OK : Codes::HTTP_CREATED;
        $serializationGroup = $product ? 'product_update' : 'product_create';

        $form = $this->formFactory->createNamed('', new ProductType($this->entityManager), $product);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $product = $form->getData();

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->renderRestView($product, $code, [], [$serializationGroup]);
        }

        return $form;
    }

    /**
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
