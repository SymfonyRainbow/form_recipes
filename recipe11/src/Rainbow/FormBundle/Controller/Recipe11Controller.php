<?php

namespace Rainbow\FormBundle\Controller;

use Rainbow\FormBundle\Entity\EmailAddress;
use Rainbow\FormBundle\Form\DataMapper\DynamicValueObjectMapper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe11Controller extends Controller
{
    public function indexAction(Request $request)
    {
        $emailAddress = new EmailAddress('info', 'symfony-rainbow.com', new \DateTime());

        /* Or adding no default value object is possible too. Make sure that
         * the `data_class` and `empty_class` options are set in this case. */
        // $emailAddress = null;


        // Notice the order of the fields do not need to match the order
        // of the value object's constructor

        $form = $this->createFormBuilder($emailAddress, array(
            'data_class' => '\Rainbow\FormBundle\Entity\EmailAddress',
            'empty_data' => null,
        ))
            ->setDataMapper(new DynamicValueObjectMapper())
            ->add('domainPart', 'text')
            ->add('validFrom', 'date')
            ->add('localPart', 'text')
            ->getForm();

        $form->add('submit', 'submit');

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        return $this->render('RainbowFormBundle:Recipe11:index.html.twig', array(
            'recipe11form' => $form->createView(),
        ));
    }
}
