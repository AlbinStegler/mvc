<?php

namespace App\Card;

class BlackjackHand extends CardHand
{
    public function __construct()
    {
        parent::__construct();
    }

    public function playerWon(BlackjackHand $player, BlackjackHand $bank): bool
    {
        $playerWon = false;

        if ($bank->getSum() < $player->getSum() && $player->getSum() <= 21) {
            $playerWon = true;
        }

        return $playerWon;
    }

    private function reduceSumForAce(int $sum) {
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
