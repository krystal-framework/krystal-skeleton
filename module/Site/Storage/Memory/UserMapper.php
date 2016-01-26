<?php

namespace Site\Storage\Memory;

use Site\Storage\UserMapperInterface;

final class UserMapper implements UserMapperInterface
{
    const PARAM_DEMO_LOGIN = 'admin@example.com';
    const PARAM_DEMO_PASSWORD_HASH = 'd033e22ae348aeb5660fc2140aec35850c4da997';

    /**
     * Fetches by credentials
     * 
     * @param string $login
     * @param string $password
     * @return array
     */
    public function fetchByCredentials($login, $password)
    {
        $result = array();

        if ($login === self::PARAM_DEMO_LOGIN && $password === self::PARAM_DEMO_PASSWORD_HASH) {
            $result = array(
                'id' => '1',
                'role' => 'admin'
            );
        }

        return $result;
    }

    /**
     * Persist a user
     * 
     * @param array $data
     * @return boolean
     */
    public function persist(array $data)
    {
        // Pretend that we saved a user
        return true;
    }
}
