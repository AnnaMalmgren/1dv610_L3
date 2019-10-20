<?php 

namespace Model; 

require_once('Exceptions/RegisterUserException.php');
require_once('DAL/DbUserTable.php');
require_once('User.php');

class RegisteredUser {
    private $storage;
    private $registeredUser;

    public function __construct(UserCredentials $user) {
            $this->storage = new DbUserTable();
            $this->setRegisteredUser($user);
            $this->storage->saveUser($this->registeredUser);
    }

     public function setRegisteredUser($credentials) {
        if ($this->storage->getUser($credentials)) {
            throw new UsernameExistsException();
        }

        $this->registeredUser = new User($credentials->getUsername(), $credentials->getPassword());
    }
}
