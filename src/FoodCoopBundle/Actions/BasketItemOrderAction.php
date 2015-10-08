<?php

namespace FoodCoopBundle\Actions;

use FoodCoopBundle\Entity\Basket;
use FoodCoopBundle\Entity\BasketRepository;
use FoodCoopBundle\Entity\OrderItem;
use FoodCoopBundle\Entity\OrderItemRepository;
use FoodCoopBundle\Entity\OrderRepository;
use FoodCoopBundle\Request\Criteria;
use Codifico\Component\Actions\Action\Action;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BasketItemOrderAction implements Action
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
    ) {

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
