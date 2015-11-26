<?php

namespace Rainbow\FormBundle\Controller;

use Rainbow\FormBundle\Entity\EmailAddress;
use Rainbow\FormBundle\Form\DataMapper\ValueObjectMapper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe4Controller extends Controller
{
    public function indexAction(Request $request)
    {
        $emailAddress = new EmailAddress('info', 'symfony-rainbow.com', new \DateTime());

        /* Or adding no default value object is possible too. Make sure that
         * the `data_class` and `empty_class` options are set in this case. */
        // $emailAddress = null;

        $form = $this->createFormBuilder($emailAddress, array(
            'data_class' => '\Rainbow\FormBundle\Entity\EmailAddress',
            'empty_data' => null,
        ))
            ->setDataMapper(new ValueObjectMapper())
            ->add('localPart', 'text')
            ->add('domainPart', 'text')
            ->add('validFrom', 'date')
            ->getForm();

        $form->add('submit', 'submit');

        $form->handleRequest($request);
        if ($form->isValid()) {
            var_dump($form->getData());
        }

        return $this->render('RainbowFormBundle:Recipe4:index.html.twig', array(
            'recipe4form' => $form->createView(),
        ));
    }
}
