<?php

namespace Rainbow\FormBundle\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Data mapper that uses the constructor arguments to map form fields. This makes it independent on your 
 * form element order, plus it can use default values if form fields are missing, but that given constructor argument
 * has a default value.
 *
 * When you don't attach a value object directly to the form, you must set the `data_class` to the actual VO
 * class, PLUS you must set `empty_data` to `null`
 */
class DynamicValueObjectMapper extends ValueObjectMapper
{

    /**
     * Maps the data of a list of forms into the properties of some data.
     *
     * @param FormInterface[] $forms A list of {@link FormInterface} instances.
     * @param mixed $data Structured data.
     *
     * @throws UnexpectedTypeException if the type of the data parameter is not supported.
     */
    public function mapFormsToData($forms, &$data)
    {
        $className = "";

        // Store all form values into  fieldName => value array
        $formValues = array();
        foreach (iterator_to_array($forms) as $form) {
            /* @var $form FormInterface */

            // Store class name for when we need to use it later (@TODO: we should do this once, but doesn't matter for now)
            $className = $form->getRoot()->getConfig()->getDataClass();

            // Form element not mapped, don't process it
            if (! $form->getConfig()->getMapped()) {
                continue;
            }

            $formValues[$form->getName()] = $form->getData();
        }


        // Data is not an object? Then no data was attached to begin with
        if (! is_object($data)) {
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
            if (! isset($formValues[$name]) && ! $param->isDefaultValueAvailable()) {
                throw new Exception\InvalidArgumentException(sprintf("Form field '%s' is not found when trying to construct the value object '%s'", $name, get_class($data)));
            }

            // Add either the value, or the constructor's default value to the argument list
            $args[] = isset($formValues[$name]) ? $formValues[$name] : $param->getDefaultValue();
        }

        // instantiate a new value object with given arguments
        $data = $class->newInstanceArgs($args);
    }

}
