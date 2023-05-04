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

    public function removeCard(CardGraphic $cardToRemove)
    {
        $key = array_search($cardToRemove, $this->cards);
        if ($key !== false) {
            unset($this->cards[$key]);
        }
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

    public function getSum(): int
    {
        $sum = 0;

        foreach ($this->cards as $card) {
            $sum += $card->showCard()["value"];
        }

        return $sum;
    }

}
