<?php

require_once('LoginSystem/LoginSystemApp.php');
require_once('CardGame/CardGameApp.php');

class App {
    private $loginSystem;
    private $cardGameApp;
    private $gameView;

    public function __construct() {
        $this->loginSystem = new LoginSystemApp();
        $this->cardGameApp = new CardGameApp();
        $this->gameView = $this->cardGameApp->getGameView();
    }

    public function runApp() {
        try {
            session_start();
            $this->changeState();
            $this->renderViews();
        } catch (\Exception $e) {
            $this->loginSystem->setException();
        }
    }

    private function changeState() {
        if ($this->loginSystem->isUserLoggedIn()) {
            $this->loginSystem->getChangesLoggedInState();
            $this->cardGameApp->startApp();
        } else {
            $this->loginSystem->getChangesLoggedOutState();
        }
    }

    private function renderViews() {
        $this->loginSystem->renderViews($this->gameView);
    }
}
