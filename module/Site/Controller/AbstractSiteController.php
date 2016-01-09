<?php

namespace Site\Controller;

use Krystal\Application\Controller\AbstractController;

abstract class AbstractSiteController extends AbstractController
{
    /**
     * This method automatically gets called when this controller executes
     * 
     * @return void
     */
    protected function bootstrap()
    {
        // Append required assets
        $this->view->getPluginBag()->appendStylesheets(array(
            '@Site/bootstrap/css/bootstrap.min.css',
            '@Site/styles.css'
        ));

        // Append required script paths
        $this->view->getPluginBag()->appendScripts(array(
            '@Site/bootstrap/js/bootstrap.min.js'
        ));

        // Define the main layout
        $this->view->setLayout('__layout__');
    }
}
