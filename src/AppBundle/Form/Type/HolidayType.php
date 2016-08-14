<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Holiday;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HolidayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('begin_at', DateType::class)
            ->add('end_at', DateType::class)
            ->add('submit', SubmitType::class, array(
                'label' => 'Ajouter',
                'attr' => array('class' => 'btn btn-success')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Holiday::class,
            'data' => new Holiday()
        ));
    }
}
