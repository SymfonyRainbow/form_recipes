<?php

namespace Rainbow\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\EqualTo;

class Recipe9Controller extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder(null, array(
                'captcha_invalid_tries' => 3,
            ))
            ->add('username', 'text', array())
            ->add('password', 'password', array(
                // Hardcoded password
                'constraints' => new EqualTo('secret'),
            ))
            ->add('submit', 'submit', array('label' => 'Login'))
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        return $this->render('RainbowFormBundle:Recipe9:index.html.twig', array(
            'recipe9form' => $form->createView(),
        ));
    }
}
