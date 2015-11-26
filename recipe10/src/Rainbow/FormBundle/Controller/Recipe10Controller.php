<?php

namespace Rainbow\FormBundle\Controller;

use Rainbow\FormBundle\Form\Wizard;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;

class Recipe10Controller extends Controller
{
    public function indexAction(Request $request)
    {
        // Create the different form builders that form the wizard steps
        $initialData = array('name' => 'john doe', 'email' => 'foo@example.org');
        $form1 = $this->createFormBuilder($initialData)
            ->add('name', 'text', array(
                'constraints' => new Length(array('min' => 5)),
            ))
            ->add('email', 'text')
        ;
        $form2 = $this->createFormBuilder()
            ->add('birthday', 'birthday')
        ;
        $form3 = $this->createFormBuilder()
            ->add('Mailing', 'checkbox', array('required' => false))
            ->add('Agree', 'checkbox', array('required' => false))
        ;

        // Initialize a new wizard and add all the steps
        $wizard = new Wizard($this->get('session'), "my-wizard");
        $wizard->addStep($form1);
        $wizard->addStep($form2);
        $wizard->addStep($form3);

        // Handle request on the wizard. Takes care of returning to the previous steps
        $wizard->handleRequest($request);

        // Fetch the current form that the wizard is processing
        $form = $wizard->getCurrentForm();
        if ($form->isValid()) {

            // Check if the wizard has a next step
            if ($wizard->hasNextStep()) {
                // Manually move towards the next form step so the correct form is displayed in the template
                $form = $wizard->nextStep();
            } else {
                // No more steps, wizard has finished

                print '<pre>';
                var_dump($wizard->getFormData());
                print '</pre>';

                // Finish the wizard, so all session data is cleared and the wizard is reset
                $wizard->finish();

                return new Response('');
            }
        }

        return $this->render('RainbowFormBundle:Recipe10:index.html.twig', array(
            'recipe10form' => $form->createView(),
            'session' => $this->get('session')->all(),
        ));
    }
}
