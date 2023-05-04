<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * Class created to represent a normal cardhand where aces are worth 14
 */
class CardHand
{
    /**
     * Cards that the "hand" holds
     * @var array<CardGraphic> $cards
     */
    protected $cards;

    public function __construct()
    {
        $this->cards = [];
    }
    /**
     * Adds a card to the hand
     */
    public function add(CardGraphic $card) : void
    {
        $card->setStyle();
        $this->cards[] = $card;
    }
    /**
     * Removes the card from the hand
     */
    public function removeCard(CardGraphic $cardToRemove) : void
    {
        $key = array_search($cardToRemove, $this->cards);
        if ($key !== false) {
            unset($this->cards[$key]);
        }
    }
    /**
     * Gets cards values in a array
     */

     /**
      * @return array<array> | array $arr
      */
    public function getCards(): mixed
    {
        $arr = [];
        if (!empty($this->cards)) {
            foreach ($this->cards as $card) {
                $arr[] = $card->showCard();
            }
        }
        return $arr;
    }
    /**
     * Returns the sum of all the cards in the hand
     */
    public function getSum(): int
    {
        $sum = 0;

        foreach ($this->cards as $card) {
            $sum += $card->showCard()["value"];
        }

        return $sum;
    }

}
