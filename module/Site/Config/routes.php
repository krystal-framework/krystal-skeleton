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

    '/register/activate/(:var)' => array(
        'controller' => 'Register@activateAction'
    ),

    '/register/success' => array(
        'controller' => 'Register@successAction'
    ),

    // Recovery
    '/recovery' => array(
        'controller' => 'Recovery@indexAction'
    ),

    '/recovery/submit' => array(
        'controller' => 'Recovery@submitAction'
    ),

    '/recovery/update' => array(
        'controller' => 'Recovery@updateAction'
    ),

    '/recovery/pass/(:var)' => array(
        'controller' => 'Recovery@passAction'
    ),
    
    // Profile
    '/profile' => array(
        'controller' => 'Profile@indexAction'
    ),

    '/profile/edit' => array(
        'controller' => 'Profile@editAction'
    ),

    '/profile/destroy' => array(
        'controller' => 'Profile@destroyAction'
    )
);
