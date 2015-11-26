<?php

namespace Rainbow\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe6Controller extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('favorite_color', 'text', array(
                'html5_type' => 'color',
            ))
            ->add('delivery_dt', 'text', array(
                'html5_type' => 'datetime',
            ))
            ->add('quantity', 'text', array(
                'html5_type' => 'range',
                'attr' => array('min' => 10, 'max' => 90, 'step' => 4),
            ))
            ->add('phone', 'text', array(
                'html5_type' => 'tel',
            ))
            ->add('weeknr', 'text', array(
                'html5_type' => 'week',
            ))
            ->getForm();

        $form->add('submit', 'submit');

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        return $this->render('RainbowFormBundle:Recipe6:index.html.twig', array(
            'recipe6form' => $form->createView(),
        ));
    }
}
