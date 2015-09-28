<?php

namespace AppBundle\Controller;

use AppBundle\Actions\SupplierCreateAction;
use AppBundle\Actions\SupplierIndexAction;
use AppBundle\Actions\SupplierRemoveAction;
use AppBundle\Actions\SupplierUpdateAction;
use AppBundle\Entity\Supplier;
use FOS\RestBundle\Util\Codes;
use AppBundle\Request\Criteria;

class SupplierController
{
    use RestTrait;

    /**
     * @var SupplierCreateAction
     */
    private $create;

    /**
     * @var SupplierUpdateAction
     */
    private $update;

    /**
     * @var SupplierIndexAction
     */
    private $index;

    /**
     * @var SupplierRemoveAction
     */
    private $remove;

    /**
     * @param SupplierCreateAction $create
     * @param SupplierUpdateAction $update
     * @param SupplierIndexAction $index
     * @param SupplierRemoveAction $remove
     */
    public function __construct(SupplierCreateAction $create, SupplierUpdateAction $update, SupplierIndexAction $index, SupplierRemoveAction $remove)
    {
        $this->create = $create;
        $this->update = $update;
        $this->index = $index;
        $this->remove = $remove;
    }

    /**
     * @return \FOS\RestBundle\View\View|null
     */
    public function createAction()
    {
        return $this->renderRestView(
            $this->create->execute(),
            Codes::HTTP_CREATED,
            [],
            ['supplier_create']
        );
    }

    /**
     * @param Supplier $supplier
     * @return \FOS\RestBundle\View\View|null
     */
    public function editAction(Supplier $supplier)
    {
        return $this->renderRestView(
            $this->update->setSupplier($supplier)->execute(),
            Codes::HTTP_OK,
            [],
            ['supplier_update']
        );
    }

    /**
     * @param Criteria $criteria
     * @return \FOS\RestBundle\View\View|null
     */
    public function indexAction(Criteria $criteria)
    {
        return $this->renderRestView(
            $this->index->setCriteria($criteria)->execute(),
            Codes::HTTP_OK,
            [],
            ['supplier_index']
        );
    }

    /**
     * @param Supplier $supplier
     */
    public function removeAction(Supplier $supplier)
    {
        $this->remove->setSupplier($supplier)->execute();
    }

    /**
     * @param Supplier $supplier
     * @return \FOS\RestBundle\View\View|null
     */
    public function viewAction(Supplier $supplier)
    {
        return $this->renderRestView(
            $supplier,
            Codes::HTTP_OK,
            [],
            ['supplier_view']
        );
    }
}
