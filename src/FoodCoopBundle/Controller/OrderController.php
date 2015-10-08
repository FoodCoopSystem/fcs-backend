<?php

namespace FoodCoopBundle\Controller;

use FoodCoopBundle\Actions\OrderActivateAction;
use FoodCoopBundle\Actions\OrderCreateAction;
use FoodCoopBundle\Actions\OrderIndexAction;
use FoodCoopBundle\Actions\OrderIndexCurrentAction;
use FoodCoopBundle\Actions\OrderRemoveAction;
use FoodCoopBundle\Actions\OrderUpdateAction;
use FoodCoopBundle\Entity\Order;
use FoodCoopBundle\Request\Criteria;
use FOS\RestBundle\Util\Codes;

class OrderController
{
    use RestTrait;

    /**
     * @var OrderCreateAction
     */
    private $create;

    /**
     * @var OrderUpdateAction
     */
    private $update;

    /**
     * @var OrderIndexAction
     */
    private $index;

    /**
     * @var OrderRemoveAction
     */
    private $remove;

    /**
     * @var OrderActivateAction
     */
    private $activate;

    /**
     * @var OrderIndexCurrentAction
     */
    private $current;

    public function __construct(
        OrderCreateAction $create,
        OrderUpdateAction $update,
        OrderIndexAction $index,
        OrderRemoveAction $remove,
        OrderActivateAction $activate,
        OrderIndexCurrentAction $current
    )
    {
        $this->create = $create;
        $this->update = $update;
        $this->index = $index;
        $this->remove = $remove;
        $this->activate = $activate;
        $this->current = $current;
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
            ['orders_index']
        );
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    public function createAction()
    {
        return $this->renderRestView(
            $this->create->execute(),
            Codes::HTTP_CREATED,
            [],
            ['orders_create']
        );
    }

    /**
     * @param Order $order
     * @return \FOS\RestBundle\View\View|null
     */
    public function editAction(Order $order)
    {
        return $this->renderRestView(
            $this->update->setOrder($order)->execute(),
            Codes::HTTP_OK,
            [],
            ['orders_update']
        );
    }

    /**
     * @param Order $order
     */
    public function removeAction(Order $order)
    {
        $this->remove->setOrder($order)->execute();
    }

    /**
     * @param Order $order
     *
     * @return \FOS\RestBundle\View\View
     */
    public function viewAction(Order $order)
    {
        return $this->renderRestView($order, Codes::HTTP_OK, [], ['orders_view']);
    }

    /**
     * @param Order $order
     * @return \FOS\RestBundle\View\View
     */
    public function activateAction(Order $order)
    {
        $this->activate->setOrder($order)->execute();
    }

    /**
     * @param Criteria $criteria
     *
     * @return \FOS\RestBundle\View\View
     */
    public function currentAction(Criteria $criteria)
    {
        return $this->renderRestView(
            $this->current->setCriteria($criteria)->execute(),
            Codes::HTTP_OK,
            [],
            ['order_list']
        );
    }
}
