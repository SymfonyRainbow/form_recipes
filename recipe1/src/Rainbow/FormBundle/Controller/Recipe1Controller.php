<?php

namespace Rainbow\FormBundle\Controller;

use Rainbow\FormBundle\Form\SizeType;
use Rainbow\FormBundle\Form\SuffixConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe1Controller extends Controller
{
    public function indexAction(Request $request)
    {
        $event = array(
            'path' => '/wwwroot',
            'size' => 1048576,
            'size2' => 1024 * 6,
            'size3' => 1024 * 650,
        );

        $form = $this->createFormBuilder($event)
            ->add('path', 'text')
            ->add('size', new SizeType(), array(
                'select_suffix' => true,
            ))
            ->add('size2', new SizeType(), array(
                'select_suffix' => false,
                'allow_suffix_in_text' => true,
            ))
            ->add('size3', new SizeType(), array(
                'select_suffix' => false,
                'allow_suffix_in_text' => false,
            ))

            ->getForm();
        $form->add('submit', 'submit');

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        return $this->render('RainbowFormBundle:Recipe1:index.html.twig', array(
            'recipe1form' => $form->createView(),
        ));
    }
}
