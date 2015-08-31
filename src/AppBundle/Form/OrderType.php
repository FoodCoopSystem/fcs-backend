<?php

namespace AppBundle\Form;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            'data_class' => 'AppBundle\Entity\Order',
            'empty_data' => function (FormInterface $form) {
                $executionAt = $form->get('executionAt')->getData() ?: new \DateTime();

                return new Order(
                    $executionAt
                );
            }
        ]);
    }

    public function getName()
    {
        return 'order';
    }
}
