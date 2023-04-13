<?php

namespace App\Card;
use App\Card\Card;

class CardHand {
    protected $cards= [];

    public function __construct() {
        $this->cards = null;
    }

    public function add(Card $card) {
        $this->cards[] = $card;
    }

    public function getCards() : string {
        $toString = "";
        if ($this->cards != null) {
            foreach ($this->cards as $card) {
                $toString += $card->toArr();
            }
        }
        return $toString;
    }

}