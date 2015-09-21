<?php

namespace AppBundle\Actions;

use AppBundle\Entity\Basket;
use Codifico\Component\Actions\Action\CreateAction;
use AppBundle\Event\EntityCreatedEvent;
use Codifico\Component\Actions\Repository\ActionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BasketItemCreateAction extends CreateAction
{
    private $dispatcher;

    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ActionRepositoryInterface  $repository,
        FormFactoryInterface  $formFactory,
        $type,
        UserInterface $user
        )
    {
        $this->dispatcher = $dispatcher;
        parent::__construct($repository, $formFactory, $type);
        $this->user = $user;
    }

    /**
     * @param $object
     * @return void
     */
    public function postCreate($object)
    {
        $this->dispatcher->dispatch('action.create', new EntityCreatedEvent($object));
    }

    /**
     * Creates new entity
     *
     */
    public function execute()
    {
        /** @var Basket $item */
        $item = $this->getBasketItemByProduct($this->stack->getCurrentRequest()->get('product'));

        if ($item) {
            $item->increaseQuantityBy($this->stack->getCurrentRequest()->get('quantity'));

            return $item;
        } else {
            return $this->handleForm();
        }
    }

    /**
     * @param $product
     */
    private function getBasketItemByProduct($product)
    {
        /** @var Basket $basket */
        return $this->repository->findOneBy([
            'owner' => $this->user,
            'product' => $product,
        ]);
    }

    /**
     * @return Basket|\Symfony\Component\Form\FormInterface
     */
    private function handleForm()
    {
        $item = new Basket();
        $item->setOwner($this->user);
        $form = $this->formFactory->createNamed('', $this->type, $item);
        $form->handleRequest($this->stack->getCurrentRequest());

        if ($form->isValid()) {
            $this->repository->add($item);

            $this->postCreate($item);

            return $item;
        }

        return $form;
    }
}
