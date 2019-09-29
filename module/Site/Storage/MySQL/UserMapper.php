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
     * Activates user profile by their unique token
     * 
     * @param string $token
     * @return boolean Depending on success
     */
    public function activateByToken($token)
    {
        $db = $this->db->update(self::getTableName(), array('activated' => 1))
                       ->whereEquals('token', $token);

        return (bool) $db->execute(true);
    }

    /**
     * Finds user id by their attached email
     * 
     * @param string $email
     * @return string
     */
    public function findIdByEmail($email)
    {
        return $this->fetchOneColumn('id', 'email', $email);
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
