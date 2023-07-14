<?php

namespace App\Project;

use App\Project\VisualCard;

class ProjectDeck
{
    /**
     * @var array<VisualCard> $cards
     */
    protected $cards;
    /**
     * @var int $size
     */
    protected $size;

    public function __construct()
    {
        $this->cards = [];
        $this->size = 0;
    }

    public function setupDeck(): void
    {
        $style = ["clubs", "hearts", "spades", "diamonds"];

        for ($x = 2; $x <= 14; $x++) {
            for ($i = 0; $i < 4; $i++) {
                $current = new VisualCard($x, $style[$i]);
                $this->addCard($current);
            }
        }
        $this->size = 52;
    }
    /**
     * @param VisualCard[] $usedCards
     */
    public function recreateDeck(array $cardsLeft): void
    {
        //Adding cards to deck
        foreach ($cardsLeft as $card) {
            $current = new VisualCard($card->getValue(), $card->getType());
            $this->addCard($current);
        }

        $this->size = count($cardsLeft);
    }


    public function removeCard(VisualCard $cardToRemove): bool
    {
        $key = array_search($cardToRemove, $this->cards);
        if ($key !== false) {
            unset($this->cards[$key]);
            $this->size -= 1;
            return true;
        }
        return false;
    }

    public function addCard(VisualCard $cardToAdd): void
    {
        $this->cards[] = $cardToAdd;
    }

    public function shuffleDeck(): void
    {
        shuffle($this->cards);
    }

    public function drawCard(): VisualCard
    {
        if (!empty($this->cards)) {
            $temp = array_shift($this->cards);
            $this->size -= 1;
            return $temp;
        }
        return new VisualCard(2, "No cards");
    }

    public function getDeckSize(): Int
    {
        return $this->size;
    }

    /**
     * @return array<mixed> $deck
     */
    public function getAsArray(): array
    {
        $deck = [];
        if (!empty($this->cards)) {
            foreach ($this->cards as $card) {
                $deck[] = [
                    "value" => $card->getValue(),
                    "type" => $card->getType(),
                    "style" => $card->getVisual()
                ];
            }
        }
        return $deck;
    }

    public function equal(ProjectDeck $other): bool
    {

        if ($other->getDeckSize() == $this->getDeckSize()) {
            $deck1 = $other->getAsArray();
            $deck2 = $this->getAsArray();
            for ($i = 0; $i < $other->getDeckSize() - 1; $i++) {
                if ($deck1[$i] != $deck2[$i]) {
                    return false;
                }
            }
        }
        return true;
    }
}
