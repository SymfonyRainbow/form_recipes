<?php

namespace Rainbow\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;

class DateRangeType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('period', 'choice', array(
                'choices' => array(
                    'empty_value' => '-- Select --',
                    'Today',
                    'Yesterday',
                    'Last week',
                    'Last month',
                    'Last year',
                )
            ))
            ->addModelTransformer($this)
        ;


    }

    public function getName()
    {
        return "daterange";
    }

    /**
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function reverseTransform($value)
    {
        switch ($value['period']) {
            case "0" :
                $ret = new \DateTime("now");
                break;
            case "1" :
                // "yesterday" gives back incorrect time 00:00:00
                $ret = new \DateTime("-1 day");
                break;
            case "2" :
                $ret = new \DateTime("last week");
                break;
            case "3" :
                $ret = new \DateTime("last month");
                break;
            case "4" :
                $ret = new \DateTime("last year");
                break;
            default:
                throw new TransformationFailedException();
        }

        return $ret;
    }


}
