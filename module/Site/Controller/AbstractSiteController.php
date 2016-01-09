<?php

namespace Site\Controller;

use Krystal\Application\Controller\AbstractController;
use Krystal\Validate\Renderer;

abstract class AbstractSiteController extends AbstractController
{
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

        // Append required assets
        $this->view->getPluginBag()->appendStylesheets(array(
            '@Site/bootstrap/css/bootstrap.min.css',
            '@Site/styles.css'
        ));

        // Append required script paths
        $this->view->getPluginBag()->appendScripts(array(
            '@Site/jquery.min.js',
            '@Site/bootstrap/js/bootstrap.min.js',
            '@Site/site.js'
        ));

        // Define the main layout
        $this->view->setLayout('__layout__');
    }
}