<?php

namespace Site;

use Krystal\Application\Module\AbstractModule;

final class Module extends AbstractModule
{
    /**
     * Returns routes of this module
     * 
     * @return array
     */
    public function getRoutes()
    {
        return array(
            '/site/captcha/(:var)' => array(
                'controller' => 'Site@captchaAction'
            ),

            '/' => array(
                'controller' => 'Site@indexAction'
            ),

            '/hello/(:var)' => array(
                'controller' => 'Site@helloAction',
            ),

            '/contact' => array(
                'controller' => 'Contact@indexAction'
            )
        );
    }

    /**
     * Returns prepared service instances of this module
     * 
     * @return array
     */
    public function getServiceProviders()
    {
        return array(
        
        );
    }
}
