<?php

namespace Site\Service;

use Krystal\Authentication\AuthManagerInterface;
use Krystal\Authentication\UserAuthServiceInterface;
use Krystal\Stdlib\ArrayUtils;
use Site\Storage\UserMapperInterface;
use Site\Collection\GenderCollection;

class UserService implements UserAuthServiceInterface
{
    /**
     * Authorization manager
     * 
     * @var \Krystal\Authentication\AuthManagerInterface
     */
    private $authManager;

    /**
     * Any compliant user mapper
     * 
     * @var \Site\Storage\UserMapperInterface
     */
    private $userMapper;

    /**
     * State initialization
     * 
     * @param \Krystal\Authentication\AuthManagerInterface $authManager
     * @param \Site\Storage\UserMapperInterface $userMapper
     * @return void
     */
    public function __construct(AuthManagerInterface $authManager, UserMapperInterface $userMapper)
    {
        $this->authManager = $authManager;
        $this->userMapper = $userMapper;
    }

    /**
     * Returns information about current logged-in user
     * 
     * @return mixed. NULL if not logged, Array if logged-in
     */
    public function getCurrentUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        } else {
            return $this->findById($this->getId());
        }
    }

    /**
     * Finds a user by their id
     * 
     * @param int $id User id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->userMapper->findByPk($id);
    }

    /**
     * Finds user id by their email
     * 
     * @param string $email Target email
     * @return string
     */
    public function findIdByEmail($email)
    {
        return $this->userMapper->findIdByEmail($email);
    }

    /**
     * Updates user data
     * 
     * @param array $input
     * @return boolean
     */
    public function save($input)
    {
        $genCol = new GenderCollection();

        // Prevent writing random values
        if (!$genCol->hasKey($input['gender'])){
            return false;
        }

        return $this->userMapper->persist($input);
    }

    /**
     * Registers a user
     * 
     * @param array $data
     * @return boolean
     */
    public function register(array $data)
    {
        // Override with a hash
        $data['password'] = $this->getHash($data['password']);

        // Remove unnecessary keys
        $data = ArrayUtils::arrayWithout($data, array('captcha', 'passwordConfirm'));

        // Now insert the new record safely
        return $this->userMapper->persist($data);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->authManager->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getRole()
    {
        return $this->authManager->getRole();
    }

    /**
     * Provides a hash of a string
     * 
     * @param string $string
     * @return string
     */
    private function getHash($string)
    {
        return sha1($string);
    }

    /**
     * Checks whether a user is logged in
     * 
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->authManager->isLoggedIn();
    }

    /**
     * Log-outs a user
     * 
     * @return void
     */
    public function logout()
    {
        return $this->authManager->logout();
    }

    /**
     * Attempts to authenticate a user
     * 
     * @param string $login
     * @param string $password
     * @param boolean $remember Whether to remember
     * @param boolean $hash Whether to hash password
     * @return boolean
     */
    public function authenticate($login, $password, $remember, $hash = true)
    {
        if ($hash === true) {
            $password = $this->getHash($password);
        }

        $user = $this->userMapper->fetchByCredentials($login, $password);

        // If it's not empty. then login and password are both value
        if (!empty($user)) {
            $this->authManager->storeId($user['id'])
                              ->storeRole($user['role'])
                              ->login($login, $password, $remember);
            return true;
        }

        return false;
    }
}
