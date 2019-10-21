<?php
namespace Model;
require_once("Deck.php");
require_once("CardStorage.php");
require_once("Player.php");

class Dealer Extends Player { 
    private $goal;
    private $deck;
    //used for checking if player got 5 cards.
    const CARDS_WIN = 5;
       
    public function __construct(int $goal = 16) {
        parent::__construct();
        $this->goal = $goal;
        $this->deck = new Deck();
    }

    public function setDealerHand() {
       do {
           $this->addCardToHand($this->dealCard());
       } while ($this->getScore() < $this->goal);
    }

    public function dealCard() : Card {
        return $this->deck->getACard();
    }
    
    public function isDealerWinner(Player $player) : bool {
        $dealerScore = $this->getScore();
        $playerScore = $player->getScore();
        if ($dealerScore === Player::GAME_GOAL) {
            return true;
        } else if ($dealerScore >= $playerScore && !$this->isBusted()) {
            return true;
        }
        return false;
    }
    
    // used to check if player has won to know if dealer should take cards or not.
    public function isPlayerWinner(Player $player) : bool {
        if ($player->getScore() == Player::GAME_GOAL) {
            return true;
        } else if ($this->playerWonOnNrOfCards($player)) {
            return true;
        }
        return false;
    }
    
    private function playerWonOnNrOfCards(Player $player) : bool {
        return count($player->getHand()) >= self::CARDS_WIN && !$player->isBusted();
    }
   
}