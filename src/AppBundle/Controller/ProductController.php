<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRepository;
use AppBundle\Request\Criteria;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    public function __construct(ProductRepository $repository, EntityManager $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
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
     * @Route("/{id}", name="product_remove")
     * @Method({"DELETE"})
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
}
