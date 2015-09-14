<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderItemRepository;
use AppBundle\Entity\OrderRepository;
use AppBundle\Entity\ProductRepository;
use AppBundle\Request\Criteria;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class OrderController
{
    use RestTrait;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderItemRepository
     */
    private $orderItemRepository;

    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * @param Criteria $criteria
     *
     * @return \FOS\RestBundle\View\View
     */
    public function indexAction(Criteria $criteria)
    {
        $order = $this->orderRepository->findNearest();
        $filters = $criteria->getFilters();
        $filters['order'] = $order;
        $criteria = new Criteria($filters, $criteria->getCount(), $criteria->getPage(), $criteria->getOrderBy());

        $data = [
            'total' => $this->orderItemRepository->countByCriteria($criteria),
            'result' => $this->orderItemRepository->findByCriteria($criteria),
        ];

        return $this->renderRestView($data, Codes::HTTP_OK, [], ['order_list']);
    }
}
