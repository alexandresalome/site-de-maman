<?php

namespace AppBundle\Form\Type;

use AppBundle\Price\Price;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://webmozart.io/blog/2015/09/09/value-objects-in-symfony-forms/
 */
class PriceType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', NumberType::class)
            ->add('currency', ChoiceType::class, array(
                'choices' => array('€' => 'EUR', '£' => 'GBP')
            ))
            ->setDataMapper($this)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Price::class,
            'empty_data' => function (FormInterface $form) {
                return new Price(
                    $form->get('amount')->getData(),
                    $form->get('currency')->getData()
                );
            }
        ));
    }

    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);
        $forms['amount']->setData($data ? $data->getAmount() : 0);
        $forms['currency']->setData($data ? $data->getCurrency() : 'EUR');
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $amount = $forms['amount']->getData();
        if ($amount === null) {
            $amount = '0';
        }
        $data = new Price(
            $amount,
            $forms['currency']->getData()
        );
    }
}
