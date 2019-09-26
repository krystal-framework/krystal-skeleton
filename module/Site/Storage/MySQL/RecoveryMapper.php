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
