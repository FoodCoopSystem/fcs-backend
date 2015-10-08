<?php

namespace FoodCoopBundle\Form;

use FoodCoopBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Form\Transformer\EntityToIdObjectTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BasketType extends AbstractType
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityToIdObjectTransformer($this->entityManager, Product::class);

        $builder
            ->add('id', 'text', ['mapped' => false])
            ->add($builder->create('product', 'text')->addModelTransformer($transformer))
            ->add('quantity')
        ;
    }

    public function getName()
    {
        return 'basket';
    }
}
