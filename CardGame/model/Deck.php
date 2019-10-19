<?php 
namespace Model; 

require_once("Card.php");

class Deck {

    public $cards;
     
    public function __construct() {
        $this->cards = Array();
        $this->getDeckOfCards();
        $this->shuffle();
    }

    private function getDeckOfCards() {
        for ($i = 0; $i < count(Card::RANKS); $i++) {
            foreach (Card::SUITS as $suit) {
                array_push($this->cards, new Card($suit, Card::RANKS[$i]));
            }   
        }
    }

    private function shuffle() {
        $currentCard = count($this->cards);

        while ($currentCard-- > 0)
        {
            $randomIndex = rand(0, $currentCard);
            $tempCard = $this->cards[$currentCard];
             $this->cards[$currentCard] = $this->cards[$randomIndex];
             $this->cards[$randomIndex] = $tempCard;
         }
    }

    public function getACard() : Card {
       return array_pop($this->cards);
    }

}

