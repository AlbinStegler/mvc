<?php

namespace App\Game;

use App\Card\CardHand;

class Bank
{
    protected $cardHand;

    public function __construct(CardHand $handOfCards)
    {
        $this->cardHand = $handOfCards;
    }

    public function canIDraw(int $sum): bool
    {
        if ($sum < 17) {
            return true;
        }
        return false;
    }

}
