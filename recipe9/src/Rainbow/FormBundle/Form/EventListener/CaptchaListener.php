<?php

namespace Rainbow\FormBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CaptchaListener implements EventSubscriberInterface
{
    /** @var SessionInterface */
    protected $session;
    /** @var string */
    protected $form_id;

    /**
     * @param SessionInterface $session
     * @param $form_id
     */
    public function __construct(SessionInterface $session, $form_id)
    {
        $this->session = $session;
        $this->form_id = $form_id;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => array('onPreSubmit', -255),
            FormEvents::POST_SUBMIT => array('onPostSubmit', -255),
        );
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSubmit(Formevent $event)
    {
        $form = $event->getForm();
        if ($form->isRoot() && $form->getConfig()->getOption('compound')) {

            // Fetch number of retries, or use 0 if not found
            $retry_count = $this->session->get('_captcha_retry_'.$this->form_id, 0);
            $invalid_tries = $form->getConfig()->getOption('captcha_invalid_tries');

            // When the number of tries is greater than our configured value, add captcha to the form
            if ($retry_count >= $invalid_tries) {
                $form->add('_captcha', 'captcha', array());
            }
        }
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(Formevent $event)
    {
        $form = $event->getForm();
        if ($form->isRoot() && $form->getConfig()->getOption('compound')) {
            if (!$form->isValid()) {
                $retry_count = $this->session->get('_captcha_retry_'.$this->form_id, 0) + 1;
                $this->session->set('_captcha_retry_'.$this->form_id, $retry_count);
            } else {
                $this->session->remove('_captcha_retry_'.$this->form_id);
            }
        }
    }
}
