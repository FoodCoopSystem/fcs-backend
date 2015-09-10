<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderRepository;
use AppBundle\Form\OrderType;
use AppBundle\Request\Criteria;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Util\Codes;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/orders", service="controller.orders")
 */
class OrdersController
{
    use RestTrait;

    /**
     * @var OrderRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(OrderRepository $repository, EntityManager $entityManager, FormFactoryInterface $formFactory, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->logger = $logger;
    }

    /**
     * @Route("", name="orders_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     * @param Criteria $criteria
     *
     *
     * @return \FOS\RestBundle\View\View
     */
    public function indexAction(Criteria $criteria)
    {
        $data = [
            'total' => $this->repository->countByCriteria($criteria),
            'result' => $this->repository->findByCriteria($criteria),
        ];

        return $this->renderRestView($data, Codes::HTTP_OK, [], ['orders_index']);
    }

    /**
     * @Route("", name="orders_create")
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
     * @Route("/{id}", name="orders_edit")
     * @Method({"POST"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\FormInterface
     * @throws NotFoundHttpException
     */
    public function editAction(Request $request, $id)
    {
        /** @var Order $order */
        $order = $this->repository->find($id);

        if (!$order) {
            throw new NotFoundHttpException(sprintf('Order %s does not exists', $id));
        }

        return $this->handleForm($request, $order);
    }

    /**
     * @param Request $request
     * @param null $order
     * @return \Symfony\Component\Form\FormInterface
     * @internal param $producent
     */
    private function handleForm(Request $request, $order = null)
    {
        $code = $order ? Codes::HTTP_OK : Codes::HTTP_CREATED;
        $serializationGroup = $order ? 'orders_update' : 'orders_create';

        $form = $this->formFactory->createNamed('', new OrderType(), $order);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $order = $form->getData();

            $this->entityManager->persist($order);
            $this->entityManager->flush($order);

            return $this->renderRestView($order, $code, [], [$serializationGroup]);
        }

        return $form;
    }

    /**
     * @Route("/{id}", name="orders_remove")
     * @Method({"DELETE"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @param $id
     */
    public function removeAction($id)
    {
        /** @var Order $order */
        $order = $this->repository->find($id);

        if (!$order) {
            throw new NotFoundHttpException(sprintf('Order %s does not exists', $id));
        }

        if ($order->isActive()) {
            throw new \LogicException('Order is active');
        }

        if ($order->hasItems()) {
            throw new \LogicException('Order has items');
        }

        $message = 'Deleting order: '.$order->getExecutionAt()->format('Y-m-d').'.';
        foreach ($order->getItems() as $item) {
            $message .= ' Item: User.id='.$item->getOwner()->getId()
                .' Product.id='.$item->getProduct()->getId().' quantity='.$item->getQuantity().'.';
        }

        $this->logger->info($message);

        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }

    /**
     * @Route("/{id}", name="orders_view")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @param $id
     *
     * @return \FOS\RestBundle\View\View
     */
    public function viewAction($id)
    {
        /** @var Order $order */
        $order = $this->repository->find($id);

        if (!$order) {
            throw new NotFoundHttpException(sprintf('Order %s does not exists', $id));
        }

        return $this->renderRestView($order, Codes::HTTP_OK, [], ['orders_view']);
    }

    /**
     * @Route("/{id}/activate", name="orders_activate")
     * @Method({"POST"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @param $id
     */
    public function activateAction($id)
    {
        /** @var Order $order */
        $order = $this->repository->find($id);

        if (!$order) {
            throw new NotFoundHttpException(sprintf('Order %s does not exists', $id));
        }

        $this->repository->activate($order);

        return $this->renderRestView(null, Codes::HTTP_NO_CONTENT);
    }
}
