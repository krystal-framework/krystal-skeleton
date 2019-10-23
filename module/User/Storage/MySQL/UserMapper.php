<?php

namespace User\Storage\MySQL;

use Krystal\Db\Sql\AbstractMapper;
use User\Storage\UserMapperInterface;

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
     * Find all users
     * 
     * @param int $excludedId Id of current user to be excluded from search results
     * @param array $ageRange Age ranges
     * @param integer $page Current page number
     * @param integer $itemsPerPage Items per page to be displayed
     * @return array
     */
    public function findAll($excludedId, array $ageRange = [], $page = null, $itemsPerPage = null)
    {
        // Columns to be selected
        $columns = [
            'id',
            'name',
            'gender',
            'birthday',
            'avatar'
        ];

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       ->whereEquals('activated', '1') // Show only active users
                       ->andWhereNotEquals('id', $excludedId); // Exclude currently logged-in user

        // Apply constraint
        if (!empty($ageRange) && isset($ageRange['start'], $ageRange['end'])) {
            $db->andWhereBetween('TIMESTAMPDIFF(YEAR, birthday, CURDATE())', (int) $ageRange['start'], (int) $ageRange['end']);
        }

        // Apply pagination if required
        if ($page !== null && $itemsPerPage !== null) {
            $db->paginate($page, $itemsPerPage);
        }

        return $db->queryAll();
    }

    /**
     * Find users by their associated id
     * 
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids)
    {
        // Shared columns to be selected
        $columns = array(
            'id',
            'name',
            'email',
            'birthday',
            'avatar'
        );

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       ->whereIn('id', $ids);

        return $db->queryAll();
    }

    /**
     * Finds initial registration datetime by associated token
     * 
     * @param string $token
     * @return string
     */
    public function findSinceByToken($token)
    {
        return $this->fetchOneColumn('since', 'token', $token);
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
