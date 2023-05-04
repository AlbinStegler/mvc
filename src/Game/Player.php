<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\BlackjackHand;

class Player
{
    /**
     * @var CardHand $cardHand
     */
    protected $cardHand;

    public function __construct(CardHand $handOfCards)
    {
        $this->cardHand = $handOfCards;
    }
    public function playerWon(BlackjackHand $bank): bool
    {
        $sBank = $bank->getSum();
        $sPlayer = $this->cardHand->getSum();

        if ($sBank == 21) {
            return false;
        }
        if ($sBank == $sPlayer) {
            return false;
        }
        if ($sBank < 21 && $sBank > $sPlayer) {
            return false;
        }
        if ($sPlayer == 21) {
            return true;
        }
        return true;


    }
    public function canIDraw(int $sum): bool
    {
        if ($sum < 21) {
            return true;
        }
        return false;
    }

}
