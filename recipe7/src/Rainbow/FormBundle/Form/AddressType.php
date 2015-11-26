<?php

namespace Rainbow\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', 'text')
            ->add('city', 'text')
            ->add('country', 'country')
        ;
    }

    public function getName()
    {
        return 'address';
    }
}
