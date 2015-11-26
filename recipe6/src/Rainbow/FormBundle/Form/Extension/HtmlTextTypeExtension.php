<?php

namespace Rainbow\FormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HtmlTextTypeExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!empty($options['html5_type'])) {
            // Using 'type' is important, as this is the variable used by the form view blocks
            $view->vars['type'] = $options['html5_type'];
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'html5_type' => '',
        ));
    }

    public function getExtendedType()
    {
        return 'text';
    }
}
