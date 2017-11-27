<?php

namespace AppBundle\Form;

use AppBundle\Entity\Customer;
use AppBundle\Form\Model\TransactionFilters;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class TransactionFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'date',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => DateType::HTML5_FORMAT,
                ]
            )
            ->add(
                'amount',
                MoneyType::class,
                [
                    'scale' => 2,
                ]
            )
            ->add(
                'customer',
                EntityType::class,
                [
                    'class' => Customer::class,
                    'choice_attr' => 'id',
                ]
            )
            ->add(
                'limit',
                NumberType::class,
                [
                    'constraints' => [
                        new Range([
                            'min' => 1,
                            'max' => 1000,
                        ])
                    ]
                ]
            )
            ->add(
                'offset',
                NumberType::class,
                [
                    'constraints' => [
                        new Range([
                            'min' => 0,
                        ])
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TransactionFilters::class,
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_transaction_filters';
    }
}
