<?php 
namespace Model; 

require_once("Card.php");

class Deck {

    private $cards;
     
    public function __construct() {
        $this->setDeckOfCards();
        $this->shuffle();
    }

    private function setDeckOfCards() {
        $this->cards = Array();
        for ($i = 0; $i < count(Card::RANKS); $i++) {
            foreach (Card::SUITS as $suit) {
                array_push($this->cards, new Card($suit, Card::RANKS[$i]));
            }   
        }
    }

    private function shuffle() {
        $currentCardIndex = count($this->cards);

        while ($currentCardIndex-- > 0)
        {
            $randomIndex = rand(0, $currentCardIndex);
            $tempCard = $this->cards[$currentCardIndex];
            $this->cards[$currentCardIndex] = $this->cards[$randomIndex];
            $this->cards[$randomIndex] = $tempCard;
         }
    }

    public function getACard() : Card {
        if (empty($this->cards)) {
            $this->setDeckOfCards();
            $this->shuffle();
        }
        
       return array_pop($this->cards);
    }

}

