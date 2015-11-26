<?php

namespace Rainbow\FormBundle\Form;

use Rainbow\FormBundle\Form\DataTransformer\SizeToArrayTransformer;
use Rainbow\FormBundle\Form\DataTransformer\SuffixToSizeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SizeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Don't allow suffix in text while having separate suffix drop down
        if ($options['select_suffix'] && $options['allow_suffix_in_text']) {
            throw new InvalidConfigurationException(sprintf(
                'You cannot add suffixes in the input box when you have enabled select boxes'
            ));
        }

        // Always add our size
        $builder->add('size', 'number', array(
            'error_bubbling' => true,
        ));

        // Add a view transformer that transforms "640kb" back to a number before validation
        if ($options['allow_suffix_in_text']) {
            $builder->get('size')->addViewTransformer(new SuffixToSizeTransformer());
        }

        // Add separate dropdown box with suffixes if needed
        if ($options['select_suffix']) {
            $builder->add('suffix', 'choice', array(
                'error_bubbling' => true,
                'choices' => array(
                    'GB' => 'Gigabyte',
                    'MB' => 'Megabyte',
                    'KB' => 'Kilobyte',
                    '' => 'Bytes',
               ),
            ));

            // Add transformer to transform number into either array[size, suffix]
            $builder->addViewTransformer(new SizeToArrayTransformer());
        } else {
            $builder->addViewTransformer(new CallbackTransformer(function ($value) {
                return array('size' => $value);
            }, function ($value) {
                return $value['size'];
            }));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'select_suffix' => true,
            'allow_suffix_in_text' => false,
        ));

        $resolver->setAllowedTypes(array('select_suffix' => 'bool'));
        $resolver->setAllowedTypes(array('allow_suffix_in_text' => 'bool'));
    }

    public function getName()
    {
        return 'size';
    }
}
