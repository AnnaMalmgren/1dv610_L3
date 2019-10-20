<?php

namespace Model; 

class Card {
    const SUITS = ["Spades", "Hearts", "Clubs", "Diamonds"];
    const RANKS = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
    const HIGH_ACE = 14;
    const LOW_ACE = 1;
    private $suit;
    private $rank;

    public function __construct(string $suit, int $rank) {
        $this->suit = $suit;
        $this->rank = $rank;
    }

    public function getSuit() : string {
        return $this->suit;
    }

    public function getRank() : int {
        return $this->rank;
    }

    public function isAce() : bool {
        return $this->rank == self::HIGH_ACE;
    }

    public function setLowAceRank() {
        $this->rank = self::LOW_ACE;
    }

}

