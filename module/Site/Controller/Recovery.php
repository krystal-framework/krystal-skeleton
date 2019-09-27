<?php

namespace Site\Controller;

use Krystal\Validate\Pattern;
use Site\Service\RecoveryService;

final class Recovery extends AbstractSiteController
{
    /**
     * Renders recovery page
     * 
     * @return string
     */
    public function indexAction()
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Home', '/')
                                       ->addOne('Login', $this->createUrl('Site:Auth@indexAction'))
                                       ->addOne('Password recovery');

        return $this->view->render('recovery/form-email-request');
    }

    /**
     * Process submission
     * 
     * @return string
     */
    public function submitAction()
    {
        $email = $this->request->getPost('email');

        // Build a validator
        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $this->request->getPost(),
                'definition' => array(
                    'email' => array(
                        'required' => true,
                        'rules' => array(
                            'NotEmpty' => array(
                                'message' => 'Email cannot be empty',
                            ),
                            'EmailPattern' => array(
                                'message' => 'Wrong email format supplied',
                            ),
                            'Unique' => array(
                                // If we can find attached user's id to this email, then email is correct
                                'value' => !((bool) $this->getModuleService('userService')->findIdByEmail($email)),
                                'message' => 'We could not find provided email in our database',
                            )
                        )
                    )
                )
            )
        ));

        if ($formValidator->isValid()) {
            $recoveryService = $this->getModuleService('recoveryService');

            if ($recoveryService->createRequest($email)) {
                // @TODO: Send email here

                $this->flashBag->set('success', 'We have sent instructions to your email. Please check your inbox');
            } else {
                $this->flashBag->set('warning', 'We could not find such email in our database');
            }

            return 1;

        } else {
            return $formValidator->getErrors();
        }
    }

    /**
     * Renders a form to change a password
     * 
     * @param string $token
     * @return string
     */
    public function passAction($token)
    {
        $recoveryService = $this->getModuleService('recoveryService');
        $entry = $recoveryService->findByToken($token);

        if ($entry && !RecoveryService::tokenExpired($entry['datetime'])) {
            return $this->view->render('recovery/form-change-password', array(
                'token' => $token
            ));
        } else {
            return $this->view->render('recovery/invalid-token');
        }
    }

    /**
     * Updates a password
     * 
     * @return string
     */
    public function updateAction()
    {
        // Build a validator
        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $this->request->getPost(),
                'definition' => array(
                    'password' => new Pattern\Password(),
                )
            )
        ));

        if ($formValidator->isValid()) {
            $recoveryService = $this->getModuleService('recoveryService');
            $result = $recoveryService->updatePassword(
                $this->request->getPost('password'),
                $this->request->getPost('token')
            );

            if ($result) {
                $this->flashBag->set('success', 'We have updated your password');
            } else {
                $this->flashBag->set('warning', 'An error occurred. We could not update your password');
            }

            return $this->json(array(
                'backUrl' => $this->createUrl('Site:Auth@indexAction')
            ));

        } else {
            return $formValidator->getErrors();
        }
    }
}
