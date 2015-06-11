<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Basket;
use AppBundle\Entity\BasketRepository;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRepository;
use AppBundle\Form\BasketType;
use AppBundle\Request\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/basket", service="controller.basket")
 */
class BasketController
{
    /**
     * @var BasketRepository
     */
    private $repository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(BasketRepository $repository, FormFactoryInterface $formFactory, UserInterface $user, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->formFactory = $formFactory;
        $this->user = $user;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("", name="basket_list")
     * @Method("GET")
     * @ParamConverter("queryCriteria", converter="query_criteria_converter")
     * @param Criteria $criteria
     * @return array
     */
    public function indexAction(Criteria $criteria)
    {
        return [
            'total' => $this->repository->countByCriteria($criteria),
            'result' => $this->repository->findByCriteria($criteria),
        ];
    }

    /**
     * @Route("/{id}", name="basket_update")
     * @Method("POST")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
    public function updateAction($id, Request $request)
    {
        $basket = $this->repository->findOneBy([
            'id' => $id,
            'owner' => $this->user,
        ]);

        if (!$basket) {
            throw new NotFoundHttpException(sprintf("Basket item %s does not exists", $id));
        }

        return $this->handleForm($request, $basket);
    }

    /**
     * @Route("", name="basket_create")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAction(Request $request)
    {
        $basket = new Basket();

        return $this->handleForm($request, $basket);
    }

    /**
     * @Route("/{id}", name="basket_remove")
     * @Method("DELETE")
     * @param $id
     */
    public function removeAction($id)
    {
        $basket = $this->repository->findOneBy([
            'id' => $id,
            'owner' => $this->user,
        ]);

        if (!$basket) {
            throw new NotFoundHttpException(sprintf("Basket item %s does not exists", $id));
        }

        $this->entityManager->remove($basket);
        $this->entityManager->flush($basket);
    }

    /**
     * @param Request $request
     * @param $basket
     * @return \Symfony\Component\Form\FormInterface
     */
    private function handleForm(Request $request, Basket $basket)
    {
        $form = $this->formFactory->createNamed('', new BasketType(), $basket);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $basket->setOwner($this->user);
            $this->entityManager->persist($basket);
            $this->entityManager->flush($basket);

            return $basket;
        }

        return $form;
    }
}
