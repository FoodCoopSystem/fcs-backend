<?php

namespace FoodCoopBundle\Actions;

use FoodCoopBundle\Entity\Basket;
use Codifico\Component\Actions\Action\Basic\UpdateAction;
use FoodCoopBundle\Event\EntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class BasketItemUpdateAction extends UpdateAction
{
    private $dispatcher;

    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        FormFactoryInterface $formFactory,
        $type,
        UserInterface $user
    )
    {
        $this->dispatcher = $dispatcher;
        parent::__construct($formFactory, $type);
        $this->user = $user;
    }

    /**
     * @param Basket $basket
     * @return BasketItemUpdateAction
     */
    public function setBasketItem(Basket $basket)
    {
        if (!$basket->isOwnedBy($this->user)) {
            throw new NotFoundHttpException("Basket item does not exists");
        }
        parent::setObject($basket);

        return $this;
    }

    /**
     * @param $object
     * @return void
     */
    public function postUpdate($object)
    {
        $this->dispatcher->dispatch('action.update', new EntityUpdatedEvent($object));
    }
}
