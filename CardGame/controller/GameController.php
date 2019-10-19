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
            $this->view->setGameIsStarted();
            $this->view->setPlayerHand($this->game->getPlayerHand());
            $this->view->setPlayerScore($this->game->getPlayerScore());
            if ($this->game->isPlayerWinner()) {
                $this->game->quitGame();
                $this->view->setPlayerWon();
            } else if ($this->game->isPlayerBusted()) {
                $this->game->quitGame();
                $this->view->setPlayerLost();
            }
        }
    }

    public function dealCard() {
        if($this->view->userWantsACard()) {
            $this->game->dealACard();
            $this->view->setPlayerHand($this->game->getPlayerHand());
            $this->view->setPlayerScore($this->game->getPlayerScore());
            if ($this->game->isPlayerWinner()) {
                $this->game->quitGame();
                $this->view->setPlayerWon();
            } else if ($this->game->isPlayerBusted()) {
                $this->game->quitGame();
                $this->view->setPlayerLost();
            }
        }
    }

    public function playerStand() {
        if($this->view->userWantsToStand()) {
            $this->game->hitDealer();
            $this->view->setPlayerHand($this->game->getPlayerHand());
            $this->view->setPlayerScore($this->game->getPlayerScore());
            $this->view->setDealerHand($this->game->getDealerHand());
            $this->view->setDealerScore($this->game->getDealerScore());
            if ($this->game->isDealerWinner()) {
                $this->game->quitGame();
                $this->view->setPlayerLost();
            } else {
                $this->game->quitGame();
                $this->view->setPlayerWon();
            }
        }
    }

    public function quitGame() {
        if($this->view->userWantsToQuit()) {
            $this->game->quitGame();
            $this->view->setQuitMsg();
        }

    }

}