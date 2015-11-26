<?php

namespace Rainbow\FormBundle\Controller;

use Rainbow\FormBundle\Form\RangeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe12Controller extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('event', 'text')
            ->add('date', new RangeType())
            ->add('submit', 'submit')
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        return $this->render('RainbowFormBundle:Recipe12:index.html.twig', array(
            'recipe12form' => $form->createView(),
        ));
    }
}
