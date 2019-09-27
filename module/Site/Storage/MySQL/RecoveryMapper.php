<?php

namespace Site\Storage\MySQL;

use Krystal\Db\Sql\AbstractMapper;

final class RecoveryMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return 'users_recovery';
    }

    /**
     * {@inheritDoc}
     */
    protected function getPk()
    {
        return 'id';
    }

    /**
     * Delete all tokens by user id
     * 
     * @param int $userId
     * @return boolean
     */
    public function deleteTokensByUserId($userId)
    {
        return $this->deleteByColumn('user_id', $userId);
    }

    /**
     * Finds an entry by its token
     * 
     * @param string $token
     * @return array
     */
    public function findByToken($token)
    {
        return $this->fetchByColumn('token', $token);
    }
}
