<?php

namespace Rainbow\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WizardType extends AbstractType
{
    /** @var SessionInterface */
    protected $session;

    function __construct(SessionInterface $session) {
        $this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sessionData = $this->getSessionData();

        switch ($sessionData['step']) {
            case 0 :
                $builder
                    ->add('name', 'text')
                    ->add('email', 'text')
                ;
                break;
            case 1 :
                $builder
                    ->add('birthday', 'birthday')
                ;
                break;
            case 2 :
                $builder
                    ->add('Mailing', 'checkbox', array('required' => false))
                    ->add('Agree', 'checkbox', array('required' => false))
                ;
                break;
        }

        $builder->add('_step', 'hidden', array('data' => $sessionData['step']));
//        $builder->add('_formdata', 'hidden', array('data' => $sessionData['formdata']));

        if ($sessionData['step'] != 0) {
            $builder->add('_prev', 'submit', array('label' => 'Previous'));
        }
        if ($sessionData['step'] != 2) {
            $builder->add('_next', 'submit', array('label' => 'Next'));
        } else {
            $builder->add('_next', 'submit', array('label' => 'Finish'));
        }

        $builder->add('_clear', 'submit', array('label' => 'Clear Session'));



        $builder->addEventListener(FormEvents::POST_SUBMIT, array($this, "onSubmit"));
    }

    public function onSubmit(FormEvent $event) {
        $form = $event->getForm();
        if ($form['_clear']->isClicked()) {
            $this->session->remove("form_".$this->getName());
        }

        if (isset($form['_prev']) && $form['_prev']->isClicked()) {
            // PRevious is clicked
            $a = 1;
        }

        if ($form['_next']->isClicked()) {
            $sessionData = $this->getSessionData();
            $sessionData['formdata'][$sessionData['step']] = $event->getData();
            $sessionData['step']++;
            $this->setSessionData($sessionData);
        }
    }

    protected function setSessionData($data) {
        $this->session->set("form_".$this->getName(), $data);
    }

    protected function getSessionData() {
        $sessionData = $this->session->get("form_".$this->getName(), false);

        if (! $sessionData) {
            $sessionData = array(
                'step' => 0,
                'formdata' => array(),
            );

            $this->setSessionData($sessionData);
        }

        return $sessionData;
    }


    public function getName()
    {
        return "wizard";
    }

}
