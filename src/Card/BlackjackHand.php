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
    public function getSum(): int
    {
        // if (count($this->cards) != 0) {
        //     return 0;
        // }

        $sum = 0;
        $countAces = 0;
        foreach ($this->cards as $card) {
            if ($card->showCard()["value"] == 14) {
                $sum += 11;
                // $card->setValue(11);
                $countAces++;
            } elseif ($card->showCard()["value"] < 14 && $card->showCard()["value"] > 10) {
                $sum += 10;
            // $card->setValue(10);
            } else {
                $sum += $card->showCard()["value"];
            }
        }

        if ($sum > 21) {
            foreach ($this->cards as $card) {
                if ($card->showCard()["value"] == 14) {
                    // $card->setValue(1);
                    $sum -= 10;
                    if ($sum <= 21) {
                        break;
                    }
                }
            }
        }


        return $sum;
    }

}
