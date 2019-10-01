<?php

namespace User\Controller;

use Krystal\Validate\Pattern;
use User\Collection\GenderCollection;
use Site\Controller\AbstractSiteController;

final class Register extends AbstractSiteController
{
    /**
     * Activates a profile by a token
     * 
     * @param string $token
     * @return string
     */
    public function activateAction($token)
    {
        // Try to activate by a token
        $success = $this->getModuleService('userService')->activateByToken($token);

        if ($success) {
            $this->flashBag->set('success', 'Your profile has been successfully activated. Use your email and password to enter account.');

            $this->redirectToRoute('Site:Auth@indexAction');
        } else {
            // Invalid token provided or failed to activate. Trigger 404
            return false;
        }
    }

    /**
     * Handles registration
     * 
     * @return string
     */
    public function indexAction()
    {
        if ($this->request->isPost()) {
            return $this->registerAction();
        } else {
            return $this->formAction();
        }
    }

    /**
     * Renders success page
     * 
     * @return string
     */
    public function successAction()
    {
        return $this->view->render('register/success');
    }

    /**
     * Renders registration form
     * 
     * @return string
     */
    private function formAction()
    {
        // Make sure, logged in users can not register an account
        if ($this->getAuthService()->isLoggedIn()) {
            return $this->response->redirect('/');
        } else {
            // Append breadcrumbs
            $this->view->getBreadcrumbBag()->addOne('Home', '/')
                                           ->addOne('Register');

            $genCol = new GenderCollection;

            return $this->view->render('register/form', array(
                'genders' => $genCol->getAll()
            ));
        }
    }

    /**
     * Registers a user
     * 
     * @return string
     */ 
    private function registerAction()
    {
        $input = $this->request->getPost();

        $userService = $this->getModuleService('userService');

        // Make the there's no user with similar email
        $unique = (bool) $userService->findIdByEmail($input['email']);

        // Build form validator
        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'email' => new Pattern\Email($unique),
                    'password' => new Pattern\Password(),
                    'passwordConfirm' => new Pattern\PasswordConfirmation($this->request->getPost('password')),
                    'captcha' => new Pattern\Captcha($this->captcha)
                )
            )
        ));

        if ($formValidator->isValid()) {
            // Register now
            $token = $userService->register($this->request->getPost());

            // @TODO: Send the token via email
            
            $this->flashBag->set('success', 'You have successfully registered an account');

            return $this->json(array(
                'backUrl' => $this->createUrl('Site:Register@successAction')
            ));

        } else {
            return $formValidator->getErrors();
        }
    }
}
