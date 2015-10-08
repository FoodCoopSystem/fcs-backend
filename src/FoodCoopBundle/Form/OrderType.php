<?php

namespace FoodCoopBundle\Form;

use FoodCoopBundle\Entity\Order;
use FoodCoopBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'text', ['mapped' => false])
            ->add('executionAt', 'date', [
                'widget' => 'single_text',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'FoodCoopBundle\Entity\Order',
            'empty_data' => function (FormInterface $form) {
                return new Order(
                    $form->get('executionAt')->getData()
                );
            }
        ]);
    }

    public function getName()
    {
        return 'order';
    }
}
