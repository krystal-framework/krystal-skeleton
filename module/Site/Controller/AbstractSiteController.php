<?php

namespace Site\Controller;

use Krystal\Application\Controller\AbstractController;
use Krystal\Validate\Renderer;

abstract class AbstractSiteController extends AbstractController
{
    /**
     * Global stylesheet files
     * 
     * @var array
     */
    private $stylesheets = array(
        'https://bootswatch.com/4/lux/bootstrap.min.css',
        '@Site/styles.css'
    );

    /**
     * Global scripts
     * 
     * @var array
     */
    private $scripts = array(
        '@Site/jquery-3.3.1.min.js',
        '@Site/bootstrap-4/js/bootstrap.min.js',
        '@Site/krystal.jquery.js'
    );

    /**
     * Returns shared authentication service for the site
     * 
     * @return \Site\Service\UserService
     */
    protected function getAuthService()
    {
        return $this->getModuleService('userService');
    }

    /**
     * Validates the request
     * 
     * @return void
     */
    protected function validateRequest()
    {
        // Validate CSRF token from POST requests
        if ($this->request->isPost()) {
            // Check the validity
            if (!$this->csrfProtector->isValid($this->request->getMetaCsrfToken())) {
                $this->response->setStatusCode(400);
                die('Invalid CSRF token');
            }
        }
    }

    /**
     * This method automatically gets called when this controller executes
     * 
     * @return void
     */
    protected function bootstrap()
    {
        // Validate the request on demand
        $this->validateRequest();

        // Define the default renderer for validation error messages
        $this->validatorFactory->setRenderer(new Renderer\StandardJson());

        // Define a directory where partial template fragments must be stored
        $this->view->getPartialBag()
                   ->addPartialDir($this->view->createThemePath('Site', $this->appConfig->getTheme()).'/partials/');

        // Append required assets
        $this->view->getPluginBag()->appendStylesheets($this->stylesheets);

        // Append required script paths
        $this->view->getPluginBag()->appendScripts($this->scripts);

        // Add shared variables
        $this->view->addVariables(array(
            'isLoggedIn' => $this->getAuthService()->isLoggedIn()
        ));

        // Define the main layout
        $this->view->setLayout('__layout__');
    }
}
