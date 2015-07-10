<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductRepository;
use AppBundle\Request\Criteria;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
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
}
