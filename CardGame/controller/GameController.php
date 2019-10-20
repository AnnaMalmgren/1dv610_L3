<?php 
namespace Controller;

require_once(__DIR__ . "/../model/GameTableFacade.php");

class GameController {
    
    private $game;
    private $view;

    public function __construct(\Model\GameTableFacade $game, \View\GameView $view){
        $this->game = $game;
        $this->view = $view;
    }

    public function startGame() {
        if($this->view->userWantsToStartGame()) {
            $this->game->startGame();
            $this->checkPlayerHand();
        }
    }

    private function checkPlayerHand() {
        if ($this->game->isPlayerWinner()) {
            $this->setPlayerWin();
        } else if ($this->game->isPlayerBusted()) {
            $this->setDealerWin();
        }
    }

    private function setPlayerWin() {
        $this->view->setUpdatedHands();
        $this->view->setPlayerWon();
        $this->game->quitGame();
    }

    private function setDealerWin() {
        $this->view->setUpdatedHands();
        $this->view->setPlayerLost();
        $this->game->quitGame();
    }

    public function playerHit() {
        if($this->view->userWantsACard()) {
            $this->game->dealACard();
            $this->checkPlayerHand();
        }
    }

    public function playerStand() {
        if($this->view->userWantsToStand()) {
            $this->game->hitDealer();
            $this->getGameResult();
        }
    }

    private function getGameResult() {
        if ($this->game->isDealerWinner()) {
            $this->setDealerWin();
        } else {
            $this->setPlayerWin();
        }
    }

    public function quitGame() {
        if($this->view->userWantsToQuit()) {
            $this->view->setQuitMsg();
            $this->game->quitGame();
        }
    }
}