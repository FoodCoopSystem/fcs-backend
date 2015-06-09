<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRepository;
use AppBundle\Request\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/basket", service="controller.basket")
 */
class BasketController
{
    /**
     * @var BasketRepository
     */
    private $repository;

    public function __construct(BasketRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("", name="basket_list")
     * @ParamConverter("queryCriteria", converter="query_criteria_converter")
     */
    public function indexAction(Criteria $criteria)
    {
        return [
            'total' => $this->repository->countByCriteria($criteria),
            'result' => $this->repository->findByCriteria($criteria),
        ];
    }
}
