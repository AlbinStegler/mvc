<?php

namespace App\Game;
use App\Card\CardHand;
use App\Card\BlackjackHand;


class Player
{
    protected $cardHand;

    public function __construct(CardHand $handOfCards)
    {
        $this->cardHand = $handOfCards;
    }
    public function playerWon(BlackjackHand $bank) : bool
    {
        $sBank = $bank->getSum();
        $sPlayer = $this->cardHand->getSum();
        $playerWon = false;

        if ($sBank == 21) {
            return false;
        } else {
            if ($sBank < 21 && $sBank > $sPlayer) {
                return false;
            } else {
                return true;
            }
        }
    }
    public function canIDraw(int $sum) : bool {
        if ($sum < 21) {
            return True;
        } else {
            return False;
        }
    }

}
