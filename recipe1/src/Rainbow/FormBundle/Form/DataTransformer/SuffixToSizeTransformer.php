<?php

namespace Rainbow\FormBundle\Form\DataTransformer;

use Rainbow\FormBundle\Form\SuffixConverter;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SuffixToSizeTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        $ret = SuffixConverter::toSuffix($value);
        if ($ret === false) {
            throw new TransformationFailedException('Unknown size');
        }

        return $ret['value'].' '.$ret['suffix'];
    }

    public function reverseTransform($value)
    {
        $i = 0;
        while ($i < strlen($value) && ctype_digit($value[$i])) {
            ++$i;
        }

        $number = (float) substr($value, 0, $i);
        $suffix = strtoupper(trim(substr($value, $i)));

        $number = SuffixConverter::fromSuffix($number, $suffix);
        if ($number === false) {
            throw new TransformationFailedException('Unknown suffix');
        }

        return (string) $number;
    }
}
