<?php

namespace Model;

class CardStorage {
    private $nameOfHand;

    public function __construct(string $sessionKey) {
        // used to create different session variables for player and dealer.
        $this->nameOfHand = $sessionKey;
    }

    public function hasHand() : bool {
        return isset($_SESSION[$this->nameOfHand]);
    }
    
	public function saveCard(Card $toBeSaved) {
        if ($this->hasHand()) {
            array_push($_SESSION[$this->nameOfHand], $toBeSaved);
        } else {
            $_SESSION[$this->nameOfHand] = Array($toBeSaved);
        } 
    }

    public function loadCards() {
        if ($this->hasHand()) {
            return $_SESSION[$this->nameOfHand];
        }
    }
    
    public function reset() {
        unset($_SESSION[$this->nameOfHand]);
    }
}