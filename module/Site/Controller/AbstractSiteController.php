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
            '@Site/bootstrap.min.css',
            '@Site/styles.css'
        ));

        // Define the main layout
        $this->view->setLayout('__layout__');
    }
}
