<?php

namespace Rainbow\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', 'text', array(
                'constraints' => new NotBlank(),
            ))
            ->add('city', 'text', array(
                'constraints' => new NotBlank(),
            ))
            ->add('country', 'country', array(
                'constraints' => new NotBlank(),
            ))
        ;
    }

    public function getName()
    {
        return 'address';
    }
}
