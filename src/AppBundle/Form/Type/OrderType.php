<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Order;
use AppBundle\Service\Planning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    private $planning;

    public function __construct(Planning $planning)
    {
        $this->planning = $planning;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname', TextType::class, array('label' => 'Nom et prénom'))
            ->add('phone', TextType::class, array('label' => 'Téléphone'))
            ->add('email', EmailType::class, array('label' => 'E-mail'))
            ->add('date', ChoiceType::class, array('label' => 'Jour', 'choices' => $this->planning->getAvailableTimes()))
            ->add('observations', TextareaType::class, array('label' => 'Remarques'))
            ->add('submit', SubmitType::class, array('label' => 'Commander', 'attr' => array('class' => 'btn-success')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Order::class
        ));
    }
}
