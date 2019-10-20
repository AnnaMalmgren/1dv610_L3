<?php 

namespace Model;

require_once('Exceptions/LoginUserException.php');
require_once('UserStorage.php');

class Authentication {
    private static $sessionName = 'SessionName';
    private static $userAgent = 'UserAgent';
    private $storage;
    private $loggedInUser;

    public function __construct() {
        $this->storage = new UserStorage();
    }

    public function loginUserByRequest(UserCredentials $credentials, bool $rememberMe) {
        $userInfo = $this->storage->validateRequestCredentials($credentials);
        $this->loggedInUser = $this->storage->getAuthenticatedUser();

        if ($rememberMe) {
            $this->saveCredentials();
        }

        $this->setUserSession();
    }

    public function saveCredentials() {
        $this->loggedInUser->setTempPassword();
        $this->storage->saveAuthCredentials($this->loggedInUser);
    }

    private function setUserSession() {
        session_regenerate_id(); 
        $_SESSION[self::$sessionName] = $this->loggedInUser->getUsername();
        $_SESSION[self::$userAgent] =  $this->getClientsBrowserName();
    }

    private function getClientsBrowserName() {
        return $_SERVER["HTTP_USER_AGENT"];
    }

    public function loginUserByAuth(UserCredentials $credentials) {
        $userInfo = $this->storage->validateAuthCredentials($credentials);
        $this->loggedInUser = $this->storage->getAuthenticatedUser();
        if (!$this->validateUserBrowser()) {
            throw new HijackingException();
        }
        $this->setUserSession();
    }

    public function getLoggedInUser() {
        return $this->loggedInUser;
    }

    public function logout() {
        unset($_SESSION[self::$sessionName]);
        unset($_SESSION[self::$userAgent]);
    }

    public static function isUserLoggedIn() : bool {
       return isset($_SESSION[self::$sessionName]);
    }

    public function validateUserBrowser() : bool {
        if (isset($_SESSION[self::$userAgent])){
            return $this->checkSession();
        }
        return TRUE;
    }
    
    //TODO FIX PROTECTION SESSION HIJACKING.
    private function checkSessionBrowser() : bool {
        if (!$this->getClientsBrowserName()) {
            return FALSE;
        }
        return $this->getClientsBrowserName() === $_SESSION[self::$userAgent];
    }

}