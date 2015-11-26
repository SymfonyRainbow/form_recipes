<?php

namespace Rainbow\FormBundle\Controller;

use Rainbow\FormBundle\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe3Controller extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new OrderType());
        $form->add('submit', 'submit');

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        return $this->render('RainbowFormBundle:Recipe3:index.html.twig', array(
            'recipe3form' => $form->createView(),
        ));
    }
}
