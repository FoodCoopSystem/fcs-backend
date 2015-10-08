<?php

namespace FoodCoopBundle\Controller;

use FoodCoopBundle\Actions\BasketItemCreateAction;
use FoodCoopBundle\Actions\BasketItemIndexAction;
use FoodCoopBundle\Actions\BasketItemOrderAction;
use FoodCoopBundle\Actions\BasketItemRemoveAction;
use FoodCoopBundle\Actions\BasketItemUpdateAction;
use FoodCoopBundle\Entity\Basket;
use FoodCoopBundle\Request\Criteria;
use FOS\RestBundle\Util\Codes;

class BasketController
{
    use RestTrait;

    /**
     * @var BasketItemCreateAction
     */
    private $create;

    /**
     * @var BasketItemUpdateAction
     */
    private $update;

    /**
     * @var BasketItemIndexAction
     */
    private $index;

    /**
     * @var BasketItemRemoveAction
     */
    private $remove;

    /**
     * @var BasketItemOrderAction
     */
    private $order;

    public function __construct(
        BasketItemCreateAction $create,
        BasketItemUpdateAction $update,
        BasketItemIndexAction $index,
        BasketItemRemoveAction $remove,
        BasketItemOrderAction $order
    )
    {
        $this->create = $create;
        $this->update = $update;
        $this->index = $index;
        $this->remove = $remove;
        $this->order = $order;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAction()
    {
        return $this->renderRestView(
            $this->create->execute(),
            Codes::HTTP_CREATED,
            [],
            ['basket_create']
        );
    }



    /**
     * @param Criteria $criteria
     *
     * @return \FOS\RestBundle\View\View
     */
    public function indexAction(Criteria $criteria)
    {
        return $this->renderRestView(
            $this->index->setCriteria($criteria)->execute(),
            Codes::HTTP_OK,
            [],
            ['basket_index']
        );
    }

    /**
     * @param Basket $basket
     * @return \Symfony\Component\Form\FormInterface
     */
    public function updateAction(Basket $basket)
    {
        return $this->renderRestView(
            $this->update->setBasketItem($basket)->execute(),
            Codes::HTTP_OK,
            [],
            ['basket_update']
        );
    }


    /**
     * @param Basket $basket
     */
    public function removeAction(Basket $basket)
    {
        $this->remove->setBasketItem($basket)->execute();
    }

    public function orderAction()
    {
        $this->order->execute();
    }
}
