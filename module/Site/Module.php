<?php

namespace Site;

use Krystal\Application\Module\AbstractModule;
use Site\Service\UserService;
use Site\Service\RecoveryService;
use Site\Storage\Memory\UserMapper;

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
        $userMapper = $this->createMapper('\Site\Storage\MySQL\UserMapper'); // or just new UserMapper() for memory storage
        $recoveryMapper = $this->createMapper('\Site\Storage\MySQL\RecoveryMapper'); // or just new UserMapper() for memory storage

        $authManager = $this->getServiceLocator()->get('authManager');

        $userService = new UserService($authManager, $userMapper);
        $authManager->setAuthService($userService);

        return array(
            'userService' => $userService,
            'recoveryService' => new RecoveryService($userMapper, $recoveryMapper)
        );
    }
}
