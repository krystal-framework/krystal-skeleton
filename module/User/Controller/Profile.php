<?php

namespace User\Controller;

use Krystal\Validate\Pattern;
use Krystal\Stdlib\ArrayCollection;
use User\Collection\GenderCollection;
use Site\Controller\AbstractSiteController;

final class Profile extends AbstractSiteController
{
    /**
     * Renders home page
     * 
     * @return string
     */
    public function indexAction()
    {
        // An external request?
        $id = $this->request->getQuery('id', false);
        $external = is_numeric($id); // Is it a request for external user or not?

        $userService = $this->getModuleService('userService');

        if ($external && $id != $userService->getId()) {
            $user = $userService->findById($id);
        } else {
            $external = false;
            $user = $userService->getCurrentUser();
        }

        // Not logged in
        if ($user === null) {
            return $this->redirectToRoute('User:Auth@indexAction');
        } else if ($user === false) {
            // Invalid id, trigger 404
            return false;
        } else {
            // Success
            return $this->view->render('profile/home', array(
                'user' => $user,
                'external' => $external
            ));
        }
    }

    /**
     * Destroys profile
     * 
     * @return mixed
     */
    public function destroyAction()
    {
        if ($this->request->hasQuery('run') && $this->getModuleService('userService')->destroy()) {

            $this->flashBag->set('success', 'Your profile has been destroyed');
            return $this->redirectToRoute('User:Auth@indexAction');

        } else {
            return $this->view->render('profile/destroy');
        }
    }

    /**
     * Renders edit page
     * 
     * @return string
     */
    public function editAction()
    {
        $userService = $this->getModuleService('userService');
        $user = $userService->getCurrentUser();

        if ($this->request->isGet()) {
            if ($user === null) {
                return $this->redirectToRoute('User:Auth@indexAction');
            } else {
                $genCol = new GenderCollection;

                // Save the old email
                $this->formAttribute->setOldAttribute('email', $user['email']);

                return $this->view->render('profile/edit', array(
                    'user' => $user,
                    'genders' => $genCol->getAll()
                ));
            }

        } else {
            // Report 404 if non logged-in
            if ($user === null) {
                return false;
            }

            $data = $this->request->getPost();

            // Persist new attributes
            $this->formAttribute->setNewAttributes($data);

            // Whether email checking needs to be done
            $hasChanged = $this->formAttribute->hasChanged('email') ? (bool) $userService->findIdByEmail($data['email']) : false;

            // Build form validator
            $formValidator = $this->createValidator(array(
                'input' => array(
                    'source' => $data,
                    'definition' => array(
                        'name' => new Pattern\Name(),
                        'email' => new Pattern\Email($hasChanged),
                        'password' => new Pattern\Password(array('required' => false)),
                    )
                )
            ));

            if ($formValidator->isValid()) {
                $data['id'] = $userService->getId();

                if ($userService->save($data)) {
                    $this->flashBag->set('success', 'Your settings have been updated successfully');
                } else {
                    $this->flashBag->set('warning', 'An error occurred during saving');
                }

                return 1;
            } else {
                return $formValidator->getErrors();
            }
        }
    }
}
