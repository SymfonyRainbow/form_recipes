<?php

namespace Rainbow\FormBundle\Form\DataMapper;

use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormInterface;


class DynamicValueObjectMapper extends ValueObjectMapper
{

    public function mapFormsToData($forms, &$data)
    {
        $className = '';

        // Store all form values into a "fieldName => value" array
        $formValues = array();
        foreach (iterator_to_array($forms) as $form) {
            /* @var $form FormInterface */

            // Store class name for when we need to use it later
            $className = $form->getRoot()->getConfig()->getDataClass();

            // If the form element is not mapped, then don't add to our array
            if (!$form->getConfig()->getMapped()) {
                continue;
            }

            $formValues[$form->getName()] = $form->getData();
        }

        // Data is not an object? Then no data was attached to begin with
        if (!is_object($data)) {
            // Create a new value object based on the class name configured in the form
            $class = new \ReflectionClass($className);
        } else {
            // Create a new value object based on the class name inside the data
            $class = new \ReflectionClass($data);
        }

        // Iterate all constructor arguments of our value object
        $args = array();
        foreach ($class->getConstructor()->getParameters() as $param) {
            $name = $param->getName();

            // If the argument has no form field matching, and no default value is set in the
            // value object, throw exception.
            if (!isset($formValues[$name]) && !$param->isDefaultValueAvailable()) {
                throw new Exception\InvalidArgumentException(sprintf(
                    "Form field '%s' is not found when trying to construct the value object '%s'",
                    $name, get_class($data))
                );
            }

            // Add either the value or the constructor argument's default value to the argument list
            $args[] = isset($formValues[$name]) ? $formValues[$name] : $param->getDefaultValue();
        }

        // instantiate a new value object with given arguments
        $data = $class->newInstanceArgs($args);
    }
}
