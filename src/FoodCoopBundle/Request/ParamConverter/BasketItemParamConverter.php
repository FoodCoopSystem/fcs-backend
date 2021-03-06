<?php

namespace FoodCoopBundle\Request\ParamConverter;

use FoodCoopBundle\Entity\Basket;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BasketItemParamConverter extends DoctrineParamConverter
{
    /**
     * {@inheritdoc}
     *
     * @throws \LogicException       When unable to guess how to get a Doctrine instance from the request information
     * @throws NotFoundHttpException When object not found
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        try {
            return parent::apply($request, $configuration);
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('Basket item does not exists');
        }
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Basket::class && parent::supports($configuration);
    }
}
