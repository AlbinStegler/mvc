<?php

namespace App\Card;

class Card
{
    /**
     *  @var int $value
     */
    protected $value; //14
    /**
     *  @var string $type
     */
    protected $type; //Spades

    public function __construct()
    {
        $this->value = 0;
        $this->type = "";
    }

    public function setValue(int $newValue): void
    {
        $this->value = $newValue;
    }

    public function setType(string $newType): void
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
    /**
     * @return array{"value": mixed, "type": mixed}
     */
    public function showCard(): array
    {
        $deck = [
            "value" => $this->getValue(),
            "type" => $this->getType(),
        ];
        return $deck;
    }

}
