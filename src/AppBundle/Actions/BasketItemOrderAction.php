<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Basket;
use AppBundle\Entity\BasketRepository;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\OrderItemRepository;
use AppBundle\Entity\OrderRepository;
use AppBundle\Request\Criteria;
use Codifico\Component\Actions\Action\ActionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BasketItemOrderAction implements ActionInterface
{
    /**
     * @var BasketRepository
     */
    private $basketRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OrderItemRepository
     */
    private $orderItemRepository;

    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(
        BasketRepository $basketRepository,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        EntityManagerInterface $entityManager,
        UserInterface $user
    )
    {

        $this->basketRepository = $basketRepository;
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
        $this->orderItemRepository = $orderItemRepository;
        $this->user = $user;
    }
    public function execute()
    {
        $criteria = new Criteria(
            ['owner' => $this->user],
            null,
            null,
            null
        );

        $items = $this->basketRepository->findByCriteria($criteria);
        $order = $this->orderRepository->findActive();

        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();
        try {
            $orderItems = [];
            foreach ($items as $item) {
                /** @var Basket $item */
                $previousOrderItem = $this->orderItemRepository->findOneByCriteria(new Criteria(
                    [
                        'owner' => $item->getOwner(),
                        'product' => $item->getProduct()
                    ]
                ));
                if ($previousOrderItem) {
                    /** @var OrderItem $orderItem */
                    $orderItem = $previousOrderItem;
                    $orderItem->increaseQuantityBy($item->getQuantity());
                } else {
                    $orderItem = OrderItem::createFromBasket($item, $order);
                }

                $this->entityManager->persist($orderItem);
                $this->entityManager->remove($item);
                $orderItems[] = $orderItem;
            }

            $this->entityManager->flush();
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            return $e->getMessage();
        }
    }
}
