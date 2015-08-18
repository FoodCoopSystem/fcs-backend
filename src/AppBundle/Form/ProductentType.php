<?php

namespace AppBundle\Form;

use AppBundle\Entity\Producent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Producent',
            'empty_data' => function (FormInterface $form) {
                return new Producent(
                    $form->get('name')->getData()
                );
            }
        ]);
    }

    public function getName()
    {
        return 'producent';
    }
}
