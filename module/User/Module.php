<?php

namespace User;

use Krystal\Application\Module\AbstractModule;
use User\Service\UserService;
use User\Service\RecoveryService;
use User\Storage\Memory\UserMapper;

final class Module extends AbstractModule
{
    /**
     * Returns routes of this module
     * 
     * @return array
     */
    public function getRoutes()
    {
        return include(__DIR__ . '/Config/routes.php');
    }

    /**
     * Returns prepared service instances of this module
     * 
     * @return array
     */
    public function getServiceProviders()
    {
        $userMapper = $this->createMapper('\User\Storage\MySQL\UserMapper'); // or just new UserMapper() for memory storage
        $recoveryMapper = $this->createMapper('\User\Storage\MySQL\RecoveryMapper'); // or just new UserMapper() for memory storage

        $authManager = $this->getServiceLocator()->get('authManager');

        $userService = new UserService($authManager, $userMapper);
        $authManager->setAuthService($userService);

        return array(
            'userService' => $userService,
            'recoveryService' => new RecoveryService($userMapper, $recoveryMapper)
        );
    }
}
