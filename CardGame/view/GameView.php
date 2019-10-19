<?php

namespace View;

require_once(__DIR__ . '/../model/GameTableFacade.php');

class GameView {
    const FACES = [1 => "Ace", 2 => "Two", 3 => "Three", 4 => "Four", 5 => "Five", 6 => "Six", 7 => "Seven", 
    8 => "Eight", 9 => "Nine", 10 => "Ten", 11 => "Knight", 12 => "Queen", 13 => "King", 14 => "Ace"];
    private static $startGame = "startGame";
    private static $hit = "hit";
    private static $stand = "stand";
    private static $quit = "quit";
    private $message = "";
    private $playerCards = "";
    private $playerScore = "";
    private $dealerScore = "";
    private $dealerCards = "";

    public function userWantsToStartGame() : bool {
        return isset($_POST[self::$startGame]);
    }

    public function userWantsACard() : bool {
        return isset($_POST[self::$hit]);
    }

    public function userWantsToStand() : bool {
        return isset($_POST[self::$stand]);
    }

    public function userWantsToQuit() : bool {
        return isset($_POST[self::$quit]);
    }

    public function updatePlayer($handOfCards, $score) {
        $this->setPlayerHand($handOfCards);
        $this->setPlayerScore($score);
    }

    private function setPlayerHand($handOfCards) {
        $this->playerCards .= '<h3>Your Hand</h3>' . $this->getHTMLHand($handOfCards);
    }

    private function setPlayerScore($score) {
        $this->playerScore = '<p><strong>Your score is: ' . $score . '</strong></p>';
    }

    private function getHTMLHand($cards) : string {
        $handToReturn = "";
        foreach ($cards as $card) {
             $handToReturn .= '<p>' . self::FACES[$card->getRank()] . ' of ' . $card->getSuit() . '</p>';
         }
         return $handToReturn;
     }

     public function updateDealer($handOfCards, $score) {
         $this->setDealerHand($handOfCards);
         $this->setDealerScore($score);
     }

    private function setDealerHand($handOfCards) {
        $this->dealerCards .= '<h3>Dealer Hand</h3>' . $this->getHTMLHand($handOfCards);
    }

    private function setDealerScore($score) {
        $this->dealerScore = '<p><strong>Dealers score is: ' . $score . '</strong></p>';
    }

    public function setPlayerWon() {
        $this->message = "Congratulations you won!";

    }

    public function setPlayerLost() {
        $this->message = "You Lost!";
    }
   

    public function setQuitMsg() {
        $this->message = "Thank you for playing!";
    }
  
    private function setGameActionButtons() {
        if ($this->isGameOn()) {
            return '
                <input type="submit" name="' . self::$hit .'" value="Hit"></input>
                <input type="submit" name="' . self::$stand .'" value="Stand"></input>
                <input type="submit" name="' . self::$quit .'" value="Quit"></input>';
        } else {
            return'
                <input type="submit" name="' . self::$startGame .'" value="New Game"></input>';
        }
    }

    private function isGameOn(): bool {
        $game = new \Model\GameTableFacade();
        return $game->isGameOn();
    }


    public function render($isLoggedIn) {
        if ($isLoggedIn) {
            return '
            <div>
                <h1>Welcome to Card Game 21!</h1>
                    <p>To start a new game press new game!</p>
                    <form method="Post" action"?">
                        ' . $this->setGameActionButtons() . '
                    </form>
                    <div class="cards">
                        ' . $this->playerCards .'
                        ' . $this->playerScore. '
                        ' . $this->dealerCards . '
                        ' . $this->dealerScore. '
                    </div>
                    <div class="messages">
                        <p>' . $this->message . '</p>
                    </div>
            </div>
            ';
        }
    
    }

}
