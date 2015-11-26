<?php

namespace Rainbow\FormBundle\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ValueObjectMapper implements DataMapperInterface
{
    /** @var PropertyAccessorInterface */
    protected $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            // ->enableExceptionOnInvalidIndex()  // Note: BC Break: only usable on v2.4 or higher
            ->enableMagicCall()
            ->getPropertyAccessor()
        ;
    }

    public function mapDataToForms($data, $forms)
    {
        // No data attached, so we don't need to do anything.
        if ($data === null) {
            return;
        }

        foreach (iterator_to_array($forms) as $form) {
            /* @var $form FormInterface */

            // Form element not mapped, don't process it
            if (!$form->getConfig()->getMapped()) {
                continue;
            }

            // Use either the property path option, or the name of the form field
            $propertyPath = $form->getConfig()->getPropertyPath();
            if (!$propertyPath) {
                $propertyPath = $form->getName();
            }

            $value = $this->accessor->getValue($data, $propertyPath);
            $form->setData($value);
        }
    }

    public function mapFormsToData($forms, &$data)
    {
        $className = '';

        $args = array();
        foreach (iterator_to_array($forms) as $form) {
            /* @var $form FormInterface */

            // Store class name for when we need to use it later
            $className = $form->getRoot()->getConfig()->getDataClass();

            // Form element not mapped, don't process it
            if (!$form->getConfig()->getMapped()) {
                continue;
            }

            $args[] = $form->getData();
        }

        // Data is not an object? Then no data was attached to begin with
        if (!is_object($data)) {
            // Create a new value object based on the class name configured in the form
            $class = new \ReflectionClass($className);
        } else {
            // Create a new value object based on the class name inside the data
            $class = new \ReflectionClass($data);
        }

        // Instantiate new value object with the given $args
        $data = $class->newInstanceArgs($args);
    }
}
