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
        // Page number
        $page = $this->request->getQuery('page', 1);

        // Age range
        $ageRange = $this->request->getQuery('age', []);
        $age = !empty($ageRange) ? $ageRange : ['start' => null, 'end' => null];

        return $this->view->render('search', [
            'users' => $this->getAuthService()->findAll($ageRange, $page, 12),
            'paginator' => $this->getAuthService()->getPaginator(),
            'age' => $age
        ]);
    }
}
