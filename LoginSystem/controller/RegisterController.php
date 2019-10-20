<?php 
namespace Controller;

require_once(__DIR__ . '/../model/RegisteredUser.php');

class RegisterController {
    private $userIsRegistered = FALSE;
    private $view;
    private $registeredUser;

    public function __construct(\View\RegisterView $registerView, \View\LoginView $loginView) {
        $this->view = $registerView;
        $this->loginView = $loginView;
    }
    
    public function registerUser () {
        try {
                $this->doRegisterUser();

        } catch (\View\FieldsMissingException $e) {
            $this->view->setCredentialsMissingMsg();
        } catch (\Model\ToShortUserNameException $e) {
            $this->view->setToShortUsernameMessage();
        } catch (\Model\ToShortPasswordException $e) {
            $this->view->setToShortPwdMessage();
        } catch (\Model\InvalidCharactersException $e) {
            $this->view->setInvalidCharactersMessage();
        } catch (\View\PasswordsDontMatchException $e) {
            $this->view->setPwdsDontMatchMessage();
        } catch (\Model\UsernameExistsException $e) {
            $this->view->setUserExistsMessage();
        } 
    }

    private function doRegisterUser() {
        if($this->view->userWantsToRegister()) {
            $userCredentials = $this->view->getRegUserCredentials();
            $this->registeredUser = new \Model\RegisteredUser($userCredentials);
            $this->userIsRegistered = TRUE;  
        }
    }

    public function getRegUsername() : string {
        return $this->registeredUser->getRegisteredUsersName();
    }
    
    public function getUserIsRegistered() : bool {
        return $this->userIsRegistered;
    }
}
