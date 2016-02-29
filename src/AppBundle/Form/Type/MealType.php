<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class MealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom',
            ))
            ->add('price', PriceType::class, array(
                'label' => 'Prix',
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
            ))
            ->add('position', NumberType::class, array(
                'label' => 'Position',
            ))
            ->add('active', CheckboxType::class, array(
                'label' => 'Afficher ce plat',
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Enregistrer',
                'attr' => array('class' => 'btn btn-success')
            ))
        ;
    }
}
