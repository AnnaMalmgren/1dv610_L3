<?php 
namespace Controller;

require_once(__DIR__ . "/../model/GameTableFacade.php");
require_once(__DIR__ . "/../model/Deck.php");

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
            $this->view->updatePlayer($this->game->getPlayerHand(), $this->game->getPlayerScore());
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
        $this->game->quitGame();
        $this->view->setPlayerWon();
    }

    private function setDealerWin() {
        $this->game->quitGame();
        $this->view->setPlayerLost();
    }

    public function playerHit() {
        if($this->view->userWantsACard()) {
            $this->game->dealACard();
            $this->view->updatePlayer($this->game->getPlayerHand(), $this->game->getPlayerScore());
            $this->checkPlayerHand();
        }
    }

    public function playerStand() {
        if($this->view->userWantsToStand()) {
            $this->game->hitDealer();
            $this->view->updatePlayer($this->game->getPlayerHand(), $this->game->getPlayerScore());
            $this->view->updateDealer($this->game->getDealerHand(), $this->game->getDealerScore());
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
            $this->game->quitGame();
            $this->view->setQuitMsg();
        }

    }

}