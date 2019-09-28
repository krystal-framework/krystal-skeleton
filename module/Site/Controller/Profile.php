<?php

namespace Site\Controller;

use Krystal\Stdlib\ArrayCollection;
use Site\Collection\GenderCollection;

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
            $user = $userService->getCurrentUser();
        }

        if ($user === null) {
            return $this->redirectToRoute('Site:Auth@indexAction');
        } else {
            return $this->view->render('profile/home', array(
                'user' => $user,
                'external' => $external
            ));
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
                return $this->redirectToRoute('Site:Auth@indexAction');
            } else {
                $genCol = new GenderCollection;

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
            $data['id'] = $userService->getId();

            if ($userService->save($data)) {
                $this->flashBag->set('success', 'Your settings have been updated successfully');
            } else {
                $this->flashBag->set('warning', 'An error occurred during saving');
            }

            return 1;
        }
    }
}
