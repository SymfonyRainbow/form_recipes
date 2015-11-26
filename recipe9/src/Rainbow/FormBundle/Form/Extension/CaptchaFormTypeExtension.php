<?php

namespace Rainbow\FormBundle\Form\Extension;

use Rainbow\FormBundle\Form\EventListener\CaptchaListener;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CaptchaFormTypeExtension extends AbstractTypeExtension
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
        $id = ($builder->getName() ?: get_class($builder->getType()->getInnerType()));

        $listener = new CaptchaListener($this->session, $id);
        $builder->addEventSubscriber($listener);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'captcha_invalid_tries' => 3,
        ));
    }

    /**
     * @return string
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
