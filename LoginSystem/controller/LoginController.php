<?php

namespace Controller;

require_once(__DIR__ . '/../model/Authentication.php');

class LoginController {

    private $view;
    private $auth;

    public function __construct(\View\LoginView $loginView) {
        $this->view = $loginView;
        $this->auth = new \Model\Authentication();
    }
    
    public function loginUserByRequest() {
        try {
                $this->tryLoginUser();

        } catch (\Model\UsernameMissingException $e) {
            $this->view->setNoUsernamegMsg();
        } catch (\Model\PasswordMissingException $e) {
            $this->view->setNoPasswordMsg();
        } catch (\Model\WrongCredentialsException $e) {
            $this->view->setWrongNameOrPwdMsg();
        } 
    }
   

    private function tryLoginUser() {
        if ($this->view->userWantsToLogin()) {
            $userCredentials = $this->view->getUserCredentials();
            $this->auth->loginUserByRequest($userCredentials, $this->view->rememberMe());
            $this->view->setLoggedInView($this->auth->getLoggedInUser());
        }
    }

    public function loginUserByAuth() {
        try {  
                $this->tryAuthAndLogin();

        } catch (\Model\WrongAuthCredentialsException $e) {
            $this->view->setWrongAuthCredentialsMsg();
        }
    }

    private function tryAuthAndLogin() {
        if ($this->userWantsLoginByAuth()) {
            $authCredentials = $this->view->getUserCredentials();
            $this->auth->loginUserByAuth($authCredentials);
            $this->view->setWelcomeBackMsg();
        }
    }

    private function userWantsLoginByAuth() : bool  {
        return $this->view->userWantsToAuthenticate() && !$this->auth->isUserLoggedIn();
    }

    public function logoutUser() {
        if ($this->userWantsToLogout()) {
            $this->view->setLogoutUI();
            $this->auth->endSession();
        }
    }

    private function userWantsToLogout() : bool {
        return $this->view->userWantsToLogout() && $this->auth->isUserLoggedIn();
    }
}