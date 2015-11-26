<?php

namespace Rainbow\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array())
            ->add('billing', new AddressType())
            ->add('same', 'checkbox', array('mapped' => false, 'required' => false, 'label' => 'Same as billing?'))
            ->add('shipping', new AddressType(), array('required' => false))
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (isset($data['same'])) {
                $data['shipping'] = $data['billing'];
                $event->setData($data);
            }
        });
    }

    public function getName()
    {
        return "order";
    }
}
