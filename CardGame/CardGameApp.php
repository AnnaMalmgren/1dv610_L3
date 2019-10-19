<?php
require_once("view/GameView.php");
require_once("controller/GameController.php");
require_once("model/GameTableFacade.php");

class CardGameApp {
    private $gameView;
    private $gameHandler;
    private $gameTable;

    public function __construct()
    {
        $this->gameTable = new \Model\GameTableFacade();
        $this->gameView = new \View\GameView();
        $this->gameHandler = new \Controller\GameController($this->gameTable, $this->gameView);
    }

    public function startApp() {
        $this->gameHandler->startGame();
        $this->gameHandler->playerHit();
        $this->gameHandler->playerStand();
        $this->gameHandler->quitGame();
    }

    public function getGameView() {
        return $this->gameView;
    }
}