<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Meal;
use AppBundle\Service\PhotoService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class MealType extends AbstractType
{
    /**
     * @var PhotoService
     */
    private $photoService;

    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }

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
            ->add('portuguese', CheckboxType::class, array(
                'label' => 'Drapeau portugais',
            ))
            ->add('photo', FileType::class, array(
                'label' => 'Photo',
                'mapped' => false,
                'constraints' => array(
                    new Image()
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Enregistrer',
                'attr' => array('class' => 'btn btn-success')
            ))
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();

            $meal = $form->getData();
            $file = $form->get('photo')->getData();

            if (!$form->isValid()) {
                return;
            }

            if (!$file) {
                return;
            }

            $this->photoService->upload($meal, $file);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Meal::class
        ));
    }
}
