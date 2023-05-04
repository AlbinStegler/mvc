<?php

namespace App\Card;

class CardGraphic extends Card
{
    /**
     * @var string | null $imgPath
     */
    protected $imgPath = null;

    public function __construct()
    {
        parent::__construct();
    }
    private function nrToText(int $value): string
    {
        $convertion = [
            "joker", "ones", "twos", "threes", "fours", "fives", "sixes", "sevens",
            "eights", "nines", "tens", "jack", "queens", "kings", "aces"
                    ];

        return $convertion[$value];
    }
    public function setStyle() : void
    {
        $folder = $this->nrToText($this->value);
        if ($this->value > 10) {
            $clothedCards = ["11" => "jack", "12" => "queen", "13" => "king", "14" => "ace"];
            $this->imgPath = "img/cards/" . $folder . "/" . $clothedCards[$this->value] . "_of_" .$this->type . ".svg";
        } if ($this->value <= 10) {
            $this->imgPath = "img/cards/" . $folder . "/" . $this->value . "_of_" .$this->type . ".svg";
        }
    }
    /**
     * @return string | null
     */
    public function getImgPath()
    {
        return $this->imgPath;
    }
    /**
     * @return array{"value": mixed, "type": mixed, "style": mixed}
     */
    public function showCard(): array
    {
        $deck = [
            "value" => $this->getValue(),
            "type" => $this->getType(),
            "style" => $this->getImgPath()
        ];
        return $deck;
    }

}
