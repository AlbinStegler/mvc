<?php

namespace App\Project;

class ProjectHand
{
    protected $cards = [];

    public function __construct()
    {
    }

    public function addToHand(VisualCard $card)
    {
        $this->cards[] = $card;
    }

    public function removeFromHand(VisualCard $cardToRemove): bool
    {
        $key = array_search($cardToRemove, $this->cards);
        if ($key !== false) {
            unset($this->cards[$key]);
            return true;
        }
        return false;
    }

    public function getHand(): array
    {
        $hand = [];
        foreach ($this->cards as $card) {
            $hand[] = [
                "value" => $card->getValue(),
                "type" => $card->getType(),
                "style" => $card->getVisual()
            ];
        }
        return $hand;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function sortByValueDesc()
    {
        $values = [];

        foreach ($this->cards as $card) {
            $values[] = $card->getValue();
        }
        $cards = $this->cards;
        array_multisort($values, SORT_DESC, $cards);
        $this->cards = $cards;
    }
}
