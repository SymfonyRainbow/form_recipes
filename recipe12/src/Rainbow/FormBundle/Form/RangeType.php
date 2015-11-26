<?php

namespace Rainbow\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_dt', 'date', array(
                'label' => 'Start',
            ))
            ->add('end_dt', 'date', array(
                'label' => 'End',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'constraints' => new Callback(function ($value, ExecutionContextInterface $context) {
                if ($value['start_dt'] >= $value['end_dt']) {
                    $context
                        ->buildViolation('The end date must be higher than the start date')
                        ->addViolation()
                    ;
                }
            }),
        ));
    }

    public function getName()
    {
        return 'custom_range';
    }
}
