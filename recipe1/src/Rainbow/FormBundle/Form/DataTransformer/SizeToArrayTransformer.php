<?php

namespace Rainbow\FormBundle\Form\DataTransformer;

use Rainbow\FormBundle\Form\SuffixConverter;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SizeToArrayTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        $ret = SuffixConverter::toSuffix($value);
        if ($ret === false) {
            throw new TransformationFailedException('Unknown size');
        }

        return array(
            'size' => $ret['value'],
            'suffix' => $ret['suffix'],
        );
    }

    public function reverseTransform($value)
    {
        $ret = SuffixConverter::fromSuffix($value['size'], $value['suffix']);
        if ($ret === false) {
            throw new TransformationFailedException(
                sprintf('Unknown suffix "%s"', $value['suffix'])
            );
        }

        return $ret;
    }
}
