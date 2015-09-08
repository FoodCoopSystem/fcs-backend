<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Basket;
use AppBundle\Entity\BasketRepository;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\OrderItemRepository;
use AppBundle\Entity\OrderRepository;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRepository;
use AppBundle\Form\BasketType;
use AppBundle\Request\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BasketController
{
    use RestTrait;

    /**
     * @var BasketRepository
     */
    private $basketRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderItemRepository
     */
    private $orderItemRepository;

    public function __construct(BasketRepository $basketRepository, OrderRepository $orderRepository, OrderItemRepository $orderItemRepository, FormFactoryInterface $formFactory, UserInterface $user, EntityManagerInterface $entityManager)
    {
        $this->basketRepository = $basketRepository;
        $this->formFactory = $formFactory;
        $this->user = $user;
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * @ParamConverter("queryCriteria", converter="query_criteria_converter")
     * @param Criteria $criteria
     *
     * @return \FOS\RestBundle\View\View
     */
    public function indexAction(Criteria $criteria)
    {
        $filters = $criteria->getFilters();
        $filters['owner'] = $this->user;

        $criteria = new Criteria(
            $filters,
            $criteria->getCount(),
            $criteria->getPage(),
            $criteria->getOrderBy()
        );
        $data = [
            'total' => $this->basketRepository->countByCriteria($criteria),
            'result' => $this->basketRepository->findByCriteria($criteria),
        ];

        return $this->renderRestView($data, Codes::HTTP_OK, [], ['basket_index']);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
    public function updateAction($id, Request $request)
    {
        /** @var Basket $basket */
        $basket = $this->basketRepository->findOneBy([
            'id' => $id,
            'owner' => $this->user,
        ]);

        if (!$basket) {
            throw new NotFoundHttpException(sprintf("Basket item %s does not exists", $id));
        }

        return $this->handleForm($request, $basket);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAction(Request $request)
    {
        /** @var Basket $basket */
        $basket = $this->basketRepository->findOneBy([
            'owner' => $this->user,
            'product' => $request->request->get('product'),
        ]);

        if ($basket) {
            for ($i = 0; $i < (int)$request->get('quantity'); $i++) {
                $basket->incrementQuantity();
            }

            $this->entityManager->persist($basket);
            $this->entityManager->flush($basket);

            return $this->renderRestView($basket, Codes::HTTP_OK, [], ['basket_create']);
        } else {
            $basket = new Basket();

            return $this->handleForm($request, $basket);
        }

    }

    /**
     * @param $id
     */
    public function removeAction($id)
    {
        $basket = $this->basketRepository->findOneBy([
            'id' => $id,
            'owner' => $this->user,
        ]);

        if (!$basket) {
            throw new NotFoundHttpException(sprintf("Basket item %s does not exists", $id));
        }

        $this->entityManager->remove($basket);
        $this->entityManager->flush($basket);
    }

    public function orderAction()
    {
        $criteria = new Criteria(
            ['owner' => $this->user],
            null,
            null,
            null
        );

        $items = $this->basketRepository->findByCriteria($criteria);
        $order = $this->orderRepository->findNearest();

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

    /**
     * @param Request $request
     * @param $basket
     * @return \Symfony\Component\Form\FormInterface
     */
    private function handleForm(Request $request, Basket $basket)
    {
        $form = $this->formFactory->createNamed('', new BasketType($this->entityManager), $basket);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $basket->setOwner($this->user);
            $this->entityManager->persist($basket);
            $this->entityManager->flush($basket);

            return $this->renderRestView($basket, Codes::HTTP_CREATED, [], ['basket_create']);
        }

        return $form;
    }
}
