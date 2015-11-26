<?php

namespace Rainbow\FormBundle\Controller;

use Rainbow\FormBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Recipe7Controller extends Controller
{
    public function indexAction(Request $request)
    {

        /* When using the following commented code, it will create two forms both called 'form'.
           This will mess up your web debug toolbar, it will be troublesome figuring out which form
           is submitted, plus you cannot use separate themes */

        /*
        $form1 = $this->createformBuilder()
                ->add('country', 'country')
                ->getForm();
        $form2 = $this->createformBuilder()
                ->add('contact', 'text')
                ->getForm();
        */

        // The following code uses the form factory to create forms with unique names
        $formFactory = $this->get('form.factory');
        $form1 = $formFactory->createNamed('form1', new ContactType());
        $form2 = $formFactory->createNamedBuilder('form2', new ContactType())->getForm();

        // Add submit buttons
        $form1->add('submit', 'submit');
        $form2->add('submit', 'submit');

        $form1->handleRequest($request);
        if ($form1->isValid()) {
            print 'Form 1 has been submitted';
            var_dump($form1->getData());
        }

        $form2->handleRequest($request);
        if ($form2->isValid()) {
            print 'Form 2 has been submitted';
            var_dump($form2->getData());
        }

        return $this->render('RainbowFormBundle:Recipe7:index.html.twig', array(
            'recipe7form1' => $form1->createView(),
            'recipe7form2' => $form2->createView(),
        ));
    }
}
