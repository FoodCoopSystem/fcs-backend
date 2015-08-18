<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price', 'number', ['scale' => 2])
            ->add('producent')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Product',
            'empty_data' => function (FormInterface $form) {
                return new Product(
                    $form->get('name')->getData(),
                    $form->get('price')->getData(),
                    $form->get('producent')->getData()
                );
            }
        ]);
    }

    public function getName()
    {
        return 'product';
    }
}
