<?php

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
    ),

    '/auth/login' => array(
        'controller' => 'Auth@indexAction'
    ),

    '/auth/logout' => array(
        'controller' => 'Auth@logoutAction'
    ),

    '/register' => array(
        'controller' => 'Register@indexAction'
    ),

    '/register/success' => array(
        'controller' => 'Register@successAction'
    )
);
