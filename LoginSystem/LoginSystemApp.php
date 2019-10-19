<?php
require_once('LoginSystem/view/LoginView.php');
require_once('LoginSystem/view/DateTimeView.php');
require_once('LoginSystem/view/LayoutView.php');
require_once('LoginSystem/view/RegisterView.php');
require_once('LoginSystem/controller/RegisterController.php');
require_once('LoginSystem/controller/LoginController.php');

class LoginSystemApp {
    private $view;
    private $registerForm;
    private $loginForm;
    private $timeView;
    private $regController;
    private $LoginController;

    public function __construct() {
        $this->loginView = new \View\LoginView();
        $this->regView = new \View\RegisterView();
        $this->timeView = new \View\DateTimeView();
        $this->view = new \View\LayoutView();

        $this->regController = new \Controller\RegisterController($this->regView, $this->loginView);
        $this->loginController = new \Controller\LoginController($this->loginView);
    }

    public function getChangesLoggedInState() {
        $this->loginController->logoutUser();
    }

    public function getChangesLoggedOutState() {
        $this->loginController->loginUserByRequest();
        $this->loginController->loginUserByAuth();
        $this->regController->registerUser();
    }

    public function isUserLoggedIn() {
        return $this->loginView->isLoggedIn();
    }

    public function renderViews($loggedInView) {   
        if ($this->regController->getUserIsRegistered()) {
            $this->renderLoginView($loggedInView); 
        } else {
            $this->view->userClicksRegisterLink() ? 
            $this->renderRegisterView($loggedInView) : $this->renderLoginView($loggedInView);
        }
    }

    private function renderRegisterView($loggedInView) {
        $this->view->render($this->isUserLoggedIn(), $this->regView, $this->timeView, $loggedInView);
    }

    private function renderLoginView($loggedInView) {
        $this->view->render($this->isUserLoggedIn(), $this->loginView, $this->timeView, $loggedInView);
    }
}
