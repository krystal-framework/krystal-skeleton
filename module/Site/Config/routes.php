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
    )
);
