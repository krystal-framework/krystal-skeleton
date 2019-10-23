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
        $ageRange = $this->request->getQuery('age', []);
        $age = !empty($ageRange) ? $ageRange : ['start' => null, 'end' => null];

        $users = $this->getAuthService()->findAll($ageRange);

        return $this->view->render('search', [
            'users' => $users,
            'age' => $age
        ]);
    }
}
