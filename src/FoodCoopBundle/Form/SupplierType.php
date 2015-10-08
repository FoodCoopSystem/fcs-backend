<?php

namespace FoodCoopBundle\Form;

use FoodCoopBundle\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'text', ['mapped' => false])
            ->add('name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Supplier::class,
            'empty_data' => function (FormInterface $form) {
                return new Supplier(
                    $form->get('name')->getData()
                );
            }
        ]);
    }

    public function getName()
    {
        return 'supplier';
    }
}
