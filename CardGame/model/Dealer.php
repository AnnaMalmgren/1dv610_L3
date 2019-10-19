<?php
namespace Model;
require_once("Deck.php");
require_once("CardStorage.php");
require_once("Player.php");

class Dealer Extends Player { 
    private $goal;
    private $deck;
       
    public function __construct($goal = 16) {
        parent::__construct();
        $this->goal = $goal;
        $this->deck = new Deck();
    }

    public function dealCard() : Card {
        return $this->deck->getACard();
    }
    

    public function setDealerHand() {
       do {
           $this->setCard($this->dealCard());
       } while ($this->getScore() < $this->goal);
        
    }


    public function isDealerWinner(Player $player) : bool {
        $dealerScore = $this->getScore();
        $playerScore = $player->getScore();
        if ($dealerScore === Player::GAME_GOAL) {
            return true;
        }
        if ($dealerScore >= $playerScore && !$this->isBusted()) {
            return true;
        }
		return false;
    }

    public function isPlayerWinner(Player $player) : bool {
		$playerScore = $player->getScore();
		if ($playerScore == Player::GAME_GOAL) {
			return true;
        }
        if (count($player->getHand()) > 4 && !$player->isBusted()) {
			return true;
		}
		return false;
    }
   
}