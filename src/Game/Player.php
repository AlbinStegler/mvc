<?php

namespace App\Game;
use App\Card\CardHand;

class Player
{
    protected $cardHand;

    public function __construct(CardHand $handOfCards)
    {
        $this->cardHand = $handOfCards;
    }

    public function canIDraw(int $sum) : bool {
        if ($sum < 21) {
            return True;
        } else {
            return False;
        }
    }

}
