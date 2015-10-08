<?php

namespace FoodCoopBundle\Actions;


use Codifico\Component\Actions\Action\Basic\IndexAction;
use Codifico\Component\Actions\Repository\ActionRepository;
use Codifico\Component\Actions\Request\Criteria;
use Symfony\Component\Security\Core\User\UserInterface;

class BasketItemIndexAction extends IndexAction
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @param ActionRepository $repository
     */
    public function __construct(ActionRepository $repository, UserInterface $user)
    {
        parent::__construct($repository);
        $this->user = $user;
    }

    /**
     * @param Criteria $criteria
     *
     * @return IndexAction
     */
    public function setCriteria(Criteria $criteria)
    {
        $filters = $criteria->getFilters();
        $filters['owner'] = $this->user;

        $criteria = new Criteria(
            $filters,
            $criteria->getCount(),
            $criteria->getPage(),
            $criteria->getOrderBy()
        );

        return parent::setCriteria($criteria);
    }

}
