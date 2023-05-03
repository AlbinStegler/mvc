<?php

namespace App\Card;

class Card
{
    protected $value; //14
    protected $type; //Spades

    public function __construct()
    {
        $this->value = null;
        $this->type = null;
    }

    public function setValue($newValue)
    {
        $this->value = $newValue;
    }

    public function setType($newType)
    {
        $this->type = $newType;
    }

    public function getValue(): int | null
    {
        return $this->value;
    }

    public function getType(): string | null
    {
        return $this->type;
    }

    // public function toArr(): array
    // {
    //     return ["type" => $this->type, "value" => $this->value];
    // }

    public function showCard(): array
    {
        $deck = [
            "value" => $this->getValue(),
            "type" => $this->getType(),
        ];
        return $deck;
    }

}
