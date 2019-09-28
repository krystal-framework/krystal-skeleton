<?php

namespace Site\Controller;

use Krystal\Validate\Pattern;
use Site\Collection\GenderCollection;

final class Register extends AbstractSiteController
{
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
        // Build form validator
        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $this->request->getPost(),
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'email' => new Pattern\Email(),
                    'password' => new Pattern\Password(),
                    'passwordConfirm' => new Pattern\PasswordConfirmation($this->request->getPost('password')),
                    'captcha' => new Pattern\Captcha($this->captcha)
                )
            )
        ));

        if ($formValidator->isValid()) {
            // Register now
            $this->getAuthService()->register($this->request->getPost());

            $this->flashBag->set('success', 'You have successfully registered an account');

            return $this->json(array(
                'backUrl' => $this->createUrl('Site:Register@successAction')
            ));

        } else {
            return $formValidator->getErrors();
        }
    }
}
