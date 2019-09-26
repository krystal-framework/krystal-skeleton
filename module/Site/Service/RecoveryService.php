<?php

namespace Site\Service;

use Krystal\Date\TimeHelper;
use Krystal\Text\TextUtils;
use Site\Storage\MySQL\UserMapper;
use Site\Storage\MySQL\RecoveryMapper;

final class RecoveryService
{
    /**
     * User mapper instance
     * 
     * @var \Site\Storage\MySQL\UserMapper
     */
    private $userMapper;

    /**
     * Recovery mapper instance
     * 
     * @var \Site\Storage\MySQL\RecoveryMapper
     */
    private $recoveryMapper;

    /**
     * State initialization
     * 
     * @param \Site\Storage\MySQL\UserMapper $userMapper
     * @param \Site\Storage\MySQL\RecoveryMapper $recoveryMapper
     * @return void
     */
    public function __construct(UserMapper $userMapper, RecoveryMapper $recoveryMapper)
    {
        $this->userMapper = $userMapper;
        $this->recoveryMapper = $recoveryMapper;
    }

    /**
     * Fetches by token
     * 
     * @param string $token
     * @return array
     */
    public function findByToken($token)
    {
        return $this->recoveryMapper->findByToken($token);
    }

    /**
     * Updates a password by a token
     * 
     * @param string $password New password
     * @param string $token
     * @return boolean Depending on success
     */
    public function updatePassword($password, $token)
    {
        // Find entry record, first
        $entry = $this->recoveryMapper->findByToken($token);

        // Make sure the right token was supplied
        if (!empty($entry)) {
            // Delete all recovery tokens
            $this->recoveryMapper->deleteTokensByUserId($entry['user_id']);

            return $this->userMapper->persist(array(
                'id' => $entry['user_id'],
                'password' => sha1($password)
            ));
        } else {
            // Invalid token supplied
            return false;
        }
    }

    /**
     * Creates validation request
     * 
     * @param string $email
     * @return boolean
     */
    public function createRequest($email)
    {
        // Find user's id by their attached email, first
        $id = $this->userMapper->findIdByEmail($email);

        // Make sure right email supplied. If its numeric, then right email provided
        if (is_numeric($id)) {
            $data = array(
                'user_id' => $id,
                'datetime' => TimeHelper::getNow(),
                'token' => TextUtils::uniqueString()
            );

            return $this->recoveryMapper->persist($data);

        } else {
            return false;
        }
    }
}
