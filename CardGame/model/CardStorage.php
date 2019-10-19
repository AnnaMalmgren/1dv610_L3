<?php

namespace Model;

class CardStorage {
    private $sessionKey;

    public function __construct(string $sessionKey) {
        $this->sessionKey = $sessionKey;
    }
    
	public function loadCards() {
		if (isset($_SESSION[$this->sessionKey])) {
			return $_SESSION[$this->sessionKey];
		}
    }
    
	public function saveCard(Card $toBeSaved) {
        if (isset($_SESSION[$this->sessionKey])) {
            array_push($_SESSION[$this->sessionKey], $toBeSaved);
        } else {
            $_SESSION[$this->sessionKey] = Array($toBeSaved);
        } 
    }
    
    public function reset() {
        unset($_SESSION[$this->sessionKey]);
    }
}