<?php

namespace App\Card;

use App\Card\CardGraphic;
class DeckOfCards {
    protected $cards = [];
    protected $size = 52;

    public function __construct() {
        $this->setupDeck();
    }

    private function setupDeck() {
        $style = ["clubs", "hearts", "spades", "diamonds"];

        for ($x = 2; $x <= 14; $x++){
            for ($i = 0; $i < 4; $i++) {
                $current = new CardGraphic;
                $current->setValue($x);
                $current->setType($style[$i]);
                $current->setStyle();
                $this->addCard($current);
            }
        }
    }
    
    public function addCard(Card $cardToAdd) {
        $this->cards[] = $cardToAdd;
    }
    public function shuffleDeck() {
        shuffle($this->cards);
    }
    public function drawCard() : CardGraphic {
        $random = random_int(1, 52);
        $temp = $this->cards[$random];
        unset($this->cards[$random]);
        $this->size -= 1;
        return $temp;
    }

    public function getDeckSize() : Int {
        return $this->size;
    }

    public function showDeck() : array {
        $deck = [];
        foreach ($this->cards as $card) {
            $deck[] = [
                "value" => $card->getValue(),
                "type" => $card->getType(),
                "style" => $card->getImgPath()
            ];
        }
        return $deck;
    }
}