<?php 
namespace Model; 

class Card 
{
    const SUITS = ["Spades", "Hearts", "Clubs", "Diamonds"];
    const RANKS = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
    private $suit;
    private $rank;

    public function __construct($suit, $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }

    public function getSuit() {
        return $this->suit;
    }

    public function getRank() {
        return $this->rank;
    }

    public function isAce() : bool {
        return $this->rank == 14;
    }

    public function setLowAceRank() {
        $this->rank = 1;
    }

}

