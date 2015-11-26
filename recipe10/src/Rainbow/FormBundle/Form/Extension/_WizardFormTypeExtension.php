<?php

namespace Rainbow\FormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WizardFormTypeExtension extends AbstractTypeExtension
{
    /** @var SessionInterface */
    protected $session;

    function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, "preSetData"));
    }

    public function preSetData(FormEvent $event)
    {
        // Check if we have data in the session...
    }


    public function getExtendedType()
    {
        return "form";
    }

}
