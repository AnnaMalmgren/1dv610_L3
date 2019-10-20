<?php

namespace Model; 

require_once("CardStorage.php");

class Player {
    protected $cardStorage;
    const GAME_GOAL = 21;
    const NO_CARDS = 0;

    public function __construct() {
        // add class name to cardstorage to create different arrays for player and dealer cards.
        $this->cardStorage = new CardStorage(get_class($this));
    }

    public function addCardToHand (Card $card) {
       $this->cardStorage->saveCard($card); 
    }

    public function getHand() {
        return $this->cardStorage->loadCards();
    }

    public function hasStartedGame() : bool {
        return $this->cardStorage->hasHand();
    }
 
    public function getScore() : int {
        $cards = $this->getHand();
        if ($cards) {
            $aces = $this->getAces($cards);
            $cardRanks = $this->getRanksWithoutAces($cards);
            $scores = $this->getScores($cardRanks, $aces);
            return array_sum($scores);
        } else {
            return self::NO_CARDS;
        }
      
    }

    private function getAces($cards) {
        $aces = Array();
        foreach($cards as $card) {
            if ($card->isAce()) {
                array_push($aces, $card);
            }
        }
        return $aces;
    }

    private function getRanksWithoutAces($cards) {
        $cardRanks = Array();

        foreach($cards as $card) {
            if (!$card->isAce()) {
                array_push($cardRanks, $card->getRank());
            }
        }
        return $cardRanks;
    }

    private function getScores($ranks, $aces) {
        foreach($aces as $ace) {
            //If total score + 14 is over 21 Ace rank is changed to 1. 
            if (array_sum($ranks) + $ace->getRank() > self::GAME_GOAL) {
                $ace->setLowAceRank();
                array_push($ranks, $ace->getRank());
            } else {
                array_push($ranks, $ace->getRank());
            }
        }

        return $ranks;
    }


    public function isBusted() : bool {
        return $this->getScore() > self::GAME_GOAL;
    }

    public function clearHand() {
        $this->cardStorage->reset();
    }
     
}