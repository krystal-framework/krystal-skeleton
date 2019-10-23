<?php

namespace User\Controller;

use Site\Controller\AbstractSiteController;

final class Search extends AbstractSiteController
{
    /**
     * Renders search page
     * 
     * @return string
     */
    public function indexAction()
    {
        $users = $this->getAuthService()->findAll();

        return $this->view->render('search', [
            'users' => $users
        ]);
    }
}
