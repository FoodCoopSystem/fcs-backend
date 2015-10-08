<?php

namespace FoodCoopBundle\Form;

use FoodCoopBundle\Entity\Supplier;
use FoodCoopBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Form\Transformer\EntityToIdObjectTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityToIdObjectTransformer($this->entityManager, Supplier::class);

        $builder
            ->add('id', 'text', ['mapped' => false])
            ->add('name')
            ->add('description')
            ->add('price', 'number', ['scale' => 2])
            ->add($builder->create('supplier', 'text')->addModelTransformer($transformer))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'FoodCoopBundle\Entity\Product',
            'empty_data' => function (FormInterface $form) {
                return new Product(
                    $form->get('name')->getData(),
                    $form->get('price')->getData(),
                    $form->get('supplier')->getData()
                );
            }
        ]);
    }

    public function getName()
    {
        return 'product';
    }
}
