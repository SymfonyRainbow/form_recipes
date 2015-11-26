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

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMapped(false);

        $key = $builder->getName();
        if (!$this->getCaptcha($key)) {
            $this->regenerateCaptcha($key);
        }

        $builder->addEventListener(FormEvents::POST_SUBMIT, array($this, 'captchaValidator'));
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $data = $this->getCaptcha($form->getName());
        $n1 = $data['number_1'];
        $n2 = $data['number_2'];

        $view->vars['label'] = sprintf('%s + %s = ?', $n1, $n2);
        $view->vars['value'] = '';
    }

    /**
     * Event listener that will validate a captcha and adds an form error if invalid.
     *
     * @param FormEvent $event
     */
    public function captchaValidator(FormEvent $event)
    {
        $form = $event->getForm();

        // Calculate complex math problem
        $sessionData = $this->getCaptcha($form->getName());
        $result = $sessionData['number_1'] + $sessionData['number_2'];

        if ($event->getData() != $result) {
            $form->addError(new FormError('Captcha is invalid'));

            // Regenerate a new captcha sum, so it will not be the same math problem
            $this->regenerateCaptcha($form->getName());
        }
    }

    /**
     * @return null|string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'captcha';
    }

    /**
     * Fetches the captcha from the session based on the key, or returns false if not found.
     *
     * @param $key
     *
     * @return mixed
     */
    protected function getCaptcha($key)
    {
        return $this->session->get('_captcha/'.$key, false);
    }

    /**
     * Generates a new captcha math problem and stores this in the session under specified key.
     *
     * @param $key
     */
    protected function regenerateCaptcha($key)
    {
        $data = array(
            'number_1' => rand(1, 10),
            'number_2' => rand(1, 10),
        );
        $this->session->set('_captcha/'.$key, $data);
    }
}
