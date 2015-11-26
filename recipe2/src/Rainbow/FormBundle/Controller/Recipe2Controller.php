<?php

namespace Rainbow\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe2Controller extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('name', 'text')
            ->add('captcha', 'captcha')
            ->getForm()
        ;
        $form->add('submit', 'submit');

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        return $this->render('RainbowFormBundle:Recipe2:index.html.twig', array(
            'recipe2form' => $form->createView(),
        ));
    }
}
