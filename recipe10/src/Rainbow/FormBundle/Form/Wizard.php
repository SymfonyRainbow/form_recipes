<?php

namespace Rainbow\FormBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Wizard
{
    /** @var FormInterface[] */
    protected $steps = array();

    /** @var SessionInterface  */
    protected $session;

    /** @var string name of the wizard instance */
    protected $name;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session, $name)
    {
        $this->steps = array();
        $this->session = $session;
        $this->name = $name;
    }

    /**
     * Adds a new step to the wizard.
     *
     * @param FormBuilderInterface $builder
     */
    public function addStep(FormBuilderInterface $builder)
    {
        $form = $builder
            ->setAttribute('wizard', $this)
            ->setAttribute('wizard_step', count($this->steps))
            ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'preSetData'))
            ->getForm();

        $this->steps[] = $form;
    }

    /**
     * Returns the name of the current wizard instance.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Event listener that is placed on each form step.
     *
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        // Only process when we are the form's root element
        $form = $event->getForm();
        if (!$form->isRoot()) {
            return;
        }

        // Check if there is submitted form data stored in the session for this step
        $sessionData = $this->getSessionData();
        $step = $form->getConfig()->getAttribute('wizard_step');
        if (isset($sessionData['formdata'][$step])) {
            /* Replace the submitted form data with the stored session form data. This takes care
             * of displaying the correct data when returning back to a previous page */
            $event->setData($sessionData['formdata'][$step]);
        }
    }

    /**
     * Handles the wizard request submission.
     *
     * @param Request $request
     *
     * @return FormInterface
     */
    public function handleRequest(Request $request)
    {
        // Handle the current form's request (validation etc)
        $form = $this->getCurrentForm();
        $form->handleRequest($request);

        // Check if the previous button was clicked on the page
        if ($form->has('_prev') && $form->get('_prev')->isClicked()) {
            // Goto previous step
            $form = $this->previousStep();

            return $form;
        }

        // Check if the next button was clicked on the page
        if ($form->get('_next')->isClicked()) {
            // If the form data was validated properly, store this data in the session
            if ($form->isValid()) {
                $sessionData = $this->getSessionData();
                $sessionData['formdata'][$sessionData['step']] = $form->getData();
                $this->setSessionData($sessionData);
            }

            return $form;
        }
    }

    /**
     * Retrieves all the submitted form data from the session.
     *
     * @return mixed
     */
    public function getFormData()
    {
        $sessionData = $this->getSessionData();

        return $sessionData['formdata'];
    }

    /**
     * Returns the form that the wizard is currently processing.
     *
     * @return FormInterface
     */
    public function getCurrentForm()
    {
        $sessionData = $this->getSessionData();
        $step = $sessionData['step'];

        // Check if we need to add a previous and/or next buttons
        $form = $this->steps[$step];
        if (!$form->has('_next')) {
            if ($step != 0) {
                $form->add('_prev', 'submit', array('label' => 'Prev'));
            }
            $form->add('_next', 'submit', array(
                'label' => $step != count($this->steps) - 1 ? 'Next' : 'Finish'
            ));
        }

        return $form;
    }

    /**
     * Returns true when the wizard has a next step.
     *
     * @return bool
     */
    public function hasNextStep()
    {
        $sessionData = $this->session->get('wizard_'.$this->getName(), false);
        $step = $sessionData['step'];

        return $step != count($this->steps) - 1;
    }

    /**
     * Returns true when the wizard has a previous step.
     *
     * @return bool
     */
    public function hasPreviousStep()
    {
        $sessionData = $this->session->get('wizard_'.$this->getName(), false);
        $step = $sessionData['step'];

        return $step != 0;
    }

    /**
     * Clears form data inside the session.
     */
    public function finish()
    {
        $sessionData = array(
            'step' => 0,
            'formdata' => array(),
        );

        $this->setSessionData($sessionData);
    }

    /**
     * Advances the wizard to the next wizard step.
     *
     * @return FormInterface
     */
    public function nextStep()
    {
        $this->updateStep(1);

        return $this->getCurrentForm();
    }

    /**
     * Returns back one step in the wizard.
     *
     * @return FormInterface
     */
    public function previousStep()
    {
        $this->updateStep(-1);

        return $this->getCurrentForm();
    }

    /**
     * Returns to the first step.
     *
     * @return FormInterface
     */
    public function reset()
    {
        $this->finish();

        return $this->getCurrentForm();
    }

    /**
     * Update wizard step by the $step amount of steps.
     *
     * @param $step
     */
    protected function updateStep($step)
    {
        $sessionData = $this->session->get('wizard_'.$this->getName(), false);
        $sessionData['step'] += $step;

        // Make sure we don't leap outside the number of actual steps we have
        if ($sessionData['step'] > count($this->steps) - 1) {
            $sessionData['step'] = count($this->steps) - 1;
        }
        if ($sessionData['step'] < 0) {
            $sessionData['step'] = 0;
        }
        $this->session->set('wizard_'.$this->getName(), $sessionData);
    }

    /**
     * Set session data for this wizard instance.
     *
     * @param $data
     */
    protected function setSessionData($data)
    {
        $this->session->set('wizard_'.$this->getName(), $data);
    }

    /**
     * Fetches session data for this wizard instance.
     * 
     * @return array|mixed
     */
    protected function getSessionData()
    {
        $sessionData = $this->session->get('wizard_'.$this->getName(), false);

        if (!$sessionData) {
            $sessionData = array(
                'step' => 0,
                'formdata' => array(),
            );

            $this->setSessionData($sessionData);
        }

        return $sessionData;
    }
}
