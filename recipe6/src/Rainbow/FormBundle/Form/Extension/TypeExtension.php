<?php

namespace Rainbow\FormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeExtension extends AbstractTypeExtension
{

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (! empty($options['html5_type'])) {
            $view->vars['type'] = $options['html5_type'];
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('html5_type', '');
    }


    public function getExtendedType()
    {
        return "text";
    }

}
