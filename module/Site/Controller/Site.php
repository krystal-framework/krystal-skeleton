<?php

namespace Site\Controller;

use Krystal\Application\Controller\AbstractController;

final class Site extends AbstractController
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

    /**
     * Renders a CAPTCHA
     * 
     * @return void
     */
    public function captchaAction()
    {
        $this->captcha->render();
    }

    /**
     * Shows a home page
     * 
     * @return string
     */
    public function indexAction()
    {
        return $this->view->render('home');
    }

    /**
     * This simple action demonstrates how to deal with variables in route maps
     * 
     * @param string $name
     * @return string
     */
    public function helloAction($name)
    {
        return $this->view->render('hello', array(
            'name' => $name
        ));
    }

    /**
     * This action gets executed when a request to non-existing route has been made
     * 
     * @return string
     */
    public function notFoundAction()
    {
        return '404: The requested page can not be found';
    }
}
