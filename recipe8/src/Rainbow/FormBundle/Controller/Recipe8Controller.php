<?php

namespace Rainbow\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe8Controller extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('country', 'country')
            ->getForm();

        $form->add('submit', 'submit');

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        /* Try the 'second.html.twig' and 'third.html.twig' templates too */
        return $this->render('RainbowFormBundle:Recipe8:first.html.twig', array(
            'recipe8form' => $form->createView(),
        ));
    }
}
