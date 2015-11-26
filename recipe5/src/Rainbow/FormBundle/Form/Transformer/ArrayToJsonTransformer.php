<?php

namespace Rainbow\FormBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ArrayToJsonTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if ($value === null || empty($value)) {
            return json_encode(array());
        }

        if (($value = json_encode($value)) === false) {
            throw new TransformationFailedException();
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        if (($value = json_decode($value, JSON_OBJECT_AS_ARRAY)) === null) {
            throw new TransformationFailedException();
        }

        return $value;
    }
}
