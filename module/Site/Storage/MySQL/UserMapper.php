<?php

namespace Site\Storage\MySQL;

use Krystal\Db\Sql\AbstractMapper;
use Site\Storage\UserMapperInterface;

final class UserMapper extends AbstractMapper implements UserMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return 'users';
    }

    /**
     * {@inheritDoc}
     */
    protected function getPk()
    {
        return 'id';
    }

    /**
     * Fetches by credentials
     * 
     * @param string $email
     * @param string $password
     * @return array
     */
    public function fetchByCredentials($email, $password)
    {
        $db = $this->db->select('*')
                       ->from(self::getTableName())
                       ->whereEquals('email', $email)
                       ->andWhereEquals('password', $password);

        return $db->query();
    }
}
