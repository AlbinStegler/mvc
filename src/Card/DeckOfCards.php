<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    protected $cards;
    protected $size;

    public function __construct()
    {
        $this->cards = [];
        $this->size = 0;
    }

    public function setupDeck()
    {
        $style = ["clubs", "hearts", "spades", "diamonds"];

        for ($x = 2; $x <= 14; $x++) {
            for ($i = 0; $i < 4; $i++) {
                $current = new CardGraphic();
                $current->setValue($x);
                $current->setType($style[$i]);
                $current->setStyle();
                $this->addCard($current);
            }
        }
        $this->size = 52;
    }

    public function recreateDeck(array $usedCards)
    {
        $style = ["clubs", "hearts", "spades", "diamonds"];
        //Adding cards to deck
        for ($x = 2; $x <= 14; $x++) {
            for ($i = 0; $i < 4; $i++) {
                $current = new CardGraphic();
                $current->setValue($x);
                $current->setType($style[$i]);
                $current->setStyle();
                $this->addCard($current);
            }
        }
        //Removing usedCards from deck
        foreach ($usedCards as $card) {
            $this->removeCard($card);
        }

        $this->size = count($this->cards);
    }


    public function removeCard(CardGraphic $cardToRemove)
    {
        $key = array_search($cardToRemove, $this->cards);
        if ($key !== false) {
            unset($this->cards[$key]);
            $this->size -= 1;
        }
    }

    public function addCard(Card $cardToAdd)
    {
        $this->cards[] = $cardToAdd;
    }
    public function shuffleDeck()
    {
        shuffle($this->cards);
    }
    public function drawCard(): CardGraphic
    {
        $temp = array_shift($this->cards);
        $this->size -= 1;
        return $temp;
    }

    public function getDeckSize(): Int
    {
        return $this->size;
    }

    public function showDeck(): array
    {
        $deck = [];
        foreach ($this->cards as $card) {
            $deck[] = [
                "value" => $card->getValue(),
                "type" => $card->getType(),
                "style" => $card->getImgPath()
            ];
        }
        return $deck;
    }

    public function equal(DeckOfCards $other) : bool {

        if ($other->getDeckSize() == $this->getDeckSize()){
            $deck1 = $other->showDeck();
            $deck2 = $this->showDeck();
            for ($i = 0; $i < $other->getDeckSize() - 1; $i++){
                if ($deck1[$i] != $deck2[$i]){
                    return false;
                }
            }
        }
        return True;
    }
}
