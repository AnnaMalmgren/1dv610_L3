<?php 
namespace Controller;

require_once(__DIR__ . '/../model/RegisteredUser.php');
require_once(__DIR__ . '/../model/User.php');

class RegisterController {
    private $userIsRegistered = FALSE;
    private $view;
    private $loginView;

    public function __construct(\View\RegisterView $registerView, \View\LoginView $loginView) {
        $this->view = $registerView;
        $this->loginView = $loginView;
    }
    
    public function registerUser () {
        try {
                $this->doRegisterUser();

        } catch (\Model\ToShortUserNameException $e) {
            $this->view->setToShortUsernameMessage();
        } catch (\Model\ToShortPasswordException $e) {
            $this->view->setToShortPwdMessage();
        } catch (\Model\InvalidCharactersException $e) {
            $this->view->setInvalidCharactersMessage();
        } catch (\Model\UsernameExistsException $e) {
            $this->view->setUserExistsMessage();
        } 
    }
    
    //TODO BREAK OUT TO SMALLER.
    private function doRegisterUser() {
        if($this->view->userWantsToRegister()) {
            if ($this->isCredentialsValid()) {
                $user = $this->view->getUser();
                $registeredUser = new \Model\RegisteredUser($user);
                $this->setSuccesfullRegisterView($user);
            } else {
                $this->setNotValidCredentialsMsg();
            }
        }
    }

    private function isCredentialsValid() {
       return !$this->view->isFieldMissing() && $this->view->doesPasswordsMatch();
    }

    private function setSuccesfullRegisterView(\Model\User $user) {
        $this->loginView->setUserRegisteredMsg();
        $this->loginView->setUsername($user->getUsername());
        $this->userIsRegistered = TRUE;   
    }

    private function setNotValidCredentialsMsg() {
        if ($this->view->isFieldMissing()) {
            $this->view->setCredentialsMissingMsg();
        } else if (!$this->view->doesPasswordsMatch()) {
            $this->view->setPwdsDontMatchMessage();
        }
    }
    
    public function getUserIsRegistered() {
        return $this->userIsRegistered;
    }
}
