<?php

namespace Rainbow\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CaptchaType extends AbstractType
{
    /** @var SessionInterface */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMapped(false);

        $key = $builder->getName();
        if (!$this->getCaptcha($key)) {
            $this->regenerateCaptcha($key);
        }

        $builder->addEventListener(FormEvents::POST_SUBMIT, array($this, 'captchaValidator'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $data = $this->getCaptcha($form->getName());
        $n1 = $data['number_1'];
        $n2 = $data['number_2'];

        $view->vars['label'] = sprintf('%s + %s = ?', $n1, $n2);
        $view->vars['value'] = '';
    }

    public function captchaValidator(FormEvent $event)
    {
        $form = $event->getForm();

        $sessionData = $this->getCaptcha($form->getName());
        $result = $sessionData['number_1'] + $sessionData['number_2'];

        if ($event->getData() != $result) {
            $form->addError(new FormError('Captcha is invalid'));

            $this->regenerateCaptcha($form->getName());
        }
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'captcha';
    }

    private function getCaptcha($key)
    {
        return $this->session->get('_captcha/'.$key, false);
    }

    private function regenerateCaptcha($key)
    {
        $data = array(
            'number_1' => rand(1, 10),
            'number_2' => rand(1, 10),
        );
        $this->session->set('_captcha/'.$key, $data);
    }
}
