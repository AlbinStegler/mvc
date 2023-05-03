<?php

namespace App\Card;

use App\Card\CardGraphic;

class CardHand
{
    protected $cards= [];

    public function __construct()
    {
        $this->cards = [];
    }

    public function add(CardGraphic $card)
    {
        $card->setStyle();
        $this->cards[] = $card;
    }

    public function getCards(): array
    {
        $arr = [];
        if ($this->cards != null) {
            foreach ($this->cards as $card) {
                $arr[] = $card->showCard();
            }
        }
        return $arr;
    }

    // public function getSum(): int
    // {
    //     // if (count($this->cards) != 0) {
    //     //     return 0;
    //     // }

    //     // $sum = 0;
    //     // $countAces = 0;

    //     // foreach ($this->cards as $card) {
    //     //     if ($card->showCard()["value"] == 14) {
    //     //         $sum += 11;
    //     //         $card->setValue(11);
    //     //         $countAces++;
    //     //     } elseif ($card->showCard()["value"] < 14 && $card->showCard()["value"] > 10) {
    //     //         $sum += 10;
    //     //         $card->setValue(10);
    //     //     } else {
    //     //         $sum += $card->showCard()["value"];
    //     //     }
    //     // }

    //     // while ($sum > 21 && $countAces > 0) {
    //     //     $sum -= 10;
    //     //     $countAces --;
    //     // };


    //     return 0;
    // }

}
