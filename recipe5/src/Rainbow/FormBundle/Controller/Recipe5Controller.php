<?php

namespace Rainbow\FormBundle\Controller;

use Rainbow\FormBundle\Form\Transformer\ArrayToJsonTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe5Controller extends Controller
{
    public function indexAction(Request $request)
    {
        // Some random array data
        $data = array(
            'name' => array(
                'a' => 1,
                'b' => true,
                'c' => array(
                    'foo',
                    'bar',
                ),
            ),
        );

        $builder = $this->createFormBuilder($data)
            ->add('name', 'textarea', array())
            ->add('submit', 'submit')
        ;
        $builder->get('name')->addModelTransformer(new ArrayToJsonTransformer());
        $form = $builder->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        return $this->render('RainbowFormBundle:Recipe5:index.html.twig', array(
            'recipe5form' => $form->createView(),
        ));
    }
}
