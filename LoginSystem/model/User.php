<?php 

namespace Model; 

require_once('Exceptions/RegisterUserException.php');
require_once('DAL/DbUserTable.php');

class User {
    private $username;
    private $password;
    private $tempPassword;
    private $minUsernameLength = 3;
    private $minPassswordLength = 6;
    private $bytesLength = 12;

    public function __construct(string $username, string $password) {
        $this->setUsername($username);
        $this->setPassword($password);
    }

    private function setUsername(string $username) {
        if (strlen($username) < $this->minUsernameLength) {
            throw new ToShortUserNameException();
        }
        
        if ($username !== htmlentities($username)) {
            throw new InvalidCharactersException();
        }
        
        $this->username = $username;
    }

    private function setPassword(string $password) {
        if (strlen($password) < $this->minPassswordLength) {
            throw new ToShortPasswordException();
        }

        $this->password = $password;
    }

    public function getUsername() : string {
        return $this->username;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getTempPassword() : string {
        return $this->tempPassword;
    }

     public function setTempPassword() {
        $this->tempPassword = bin2hex(random_bytes($this->bytesLength));
     }

}