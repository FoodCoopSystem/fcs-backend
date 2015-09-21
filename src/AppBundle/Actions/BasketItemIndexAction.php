<?php

namespace AppBundle\Actions;


use Codifico\Component\Actions\Action\IndexAction;
use Codifico\Component\Actions\Repository\ActionRepositoryInterface;
use Codifico\Component\Actions\Request\Criteria;
use Symfony\Component\Security\Core\User\UserInterface;

class BasketItemIndexAction extends IndexAction
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @param ActionRepositoryInterface $repository
     */
    public function __construct(ActionRepositoryInterface $repository, UserInterface $user)
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
