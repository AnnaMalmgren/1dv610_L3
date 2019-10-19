<?php

namespace Model; 

require_once("CardStorage.php");

class Player {
    protected $cardStorage;
    const GAME_GOAL = 21;

    public function __construct() {
        $this->cardStorage = new CardStorage(get_class($this));
    }

    public function setCard (Card $card) {
       $this->cardStorage->saveCard($card); 
    }

    public function getHand() {
        return $this->cardStorage->loadCards();
    }

    public function hasStartedGame() {
        return $this->cardStorage->hasHand();
    }
 
    public function getScore() {
        $scores = Array();
        $aces = Array();
        $cards = $this->getHand();
        if ($cards) {
            foreach($cards as $card) {
                if ($card->isAce()) {
                    array_push($aces, $card);
                } else {
                    array_push($scores, $card->getRank());
                }
            }
        
            foreach($aces as $ace) {
                if (array_sum($scores) + $ace->getRank() > self::GAME_GOAL) {
                    $ace->setLowAceRank();
                    array_push($scores, $ace->getRank());
                } else {
                    array_push($scores, $ace->getRank());
                }
            }
            return array_sum($scores);
        } else {
            return 0;
        }
    }

    public function isBusted() : bool {
        return $this->getScore() > self::GAME_GOAL;
    }

    public function clearHand() {
        $this->cardStorage->reset();
    }
     
}