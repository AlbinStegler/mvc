<?php

namespace App\Card;

/**
* Class made to represent a card hand in the game Blackjack extends from class CardHand
*/
class BlackjackHand extends CardHand
{
    /**
     * Default constructor uses parents constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Used to see if the player won. Player won if sum is bigger than banks sum but less than 21
     */
    public function playerWon(BlackjackHand $player, BlackjackHand $bank): bool
    {
        $playerWon = false;

        if ($bank->getSum() < $player->getSum() && $player->getSum() <= 21) {
            $playerWon = true;
        }

        return $playerWon;
    }

    /**
     * Private function that lowers value of ace since ace got value of 1 and 11 in Blackjack
     */
    private function reduceSumForAce(int $sum)
    {
        foreach ($this->cards as $card) {
            if ($card->showCard()["value"] == 14) {
                $sum -= 10;
                if ($sum <= 21) {
                    return $sum;
                }
            }
        }

        return $sum;
    }

    /**
     * Calculates the sum of the hands with the rules of blackjack. Clothed cards are 10 and ace is either 1 or 11
     */
    public function getSum(): int
    {
        $sum = 0;
        $countAces = 0;
        foreach ($this->cards as $card) {
            if ($card->showCard()["value"] == 14) {
                $sum += 11;
                $countAces++;
            } elseif ($card->showCard()["value"] < 14 && $card->showCard()["value"] > 10) {
                $sum += 10;
            } elseif ($card->showCard()["value"] <= 10) {
                $sum += $card->showCard()["value"];
            }
        }

        if ($sum > 21 && $countAces > 0) {
            $sum = $this->reduceSumForAce($sum);
        }

        return $sum;
    }

}
