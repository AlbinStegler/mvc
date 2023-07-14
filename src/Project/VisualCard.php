<?php

namespace App\Project;

use App\Project\ProjectCard;

class VisualCard extends ProjectCard
{
    /**
     * @var string | null $imgPath
     */
    protected $imgPath = null;

    public function __construct()
    {
        //Kontrollerar antal param skickar till rätt konstruktor i föräldern

        $arguments = func_get_args();
        if (count($arguments) == 1) {
            parent::__construct($arguments[0]);
        }
        if (count($arguments) == 2) {
            parent::__construct($arguments[0], $arguments[1]);
        }
        $this->setStyle();
    }

    private function nrToText(int $value): string
    {
        $convertion = [
            "joker", "ones", "twos", "threes", "fours", "fives", "sixes", "sevens",
            "eights", "nines", "tens", "jack", "queens", "kings", "aces"
        ];

        return $convertion[$value];
    }

    private function setStyle(): void
    {
        $folder = $this->nrToText($this->value);
        if ($this->value > 10) {
            $clothedCards = ["11" => "jack", "12" => "queen", "13" => "king", "14" => "ace"];
            $this->imgPath = "img/cards/" . $folder . "/" . $clothedCards[$this->value] . "_of_" . $this->type . ".svg";
        }
        if ($this->value <= 10) {
            $this->imgPath = "img/cards/" . $folder . "/" . $this->value . "_of_" . $this->type . ".svg";
        }
    }
    /**
     * @return string | null
     */
    public function getVisual()
    {
        return $this->imgPath;
    }
    /**
     * @return array{"value": mixed, "type": mixed, "style": mixed}
     */
    public function showAll(): array
    {
        $deck = [
            "value" => $this->getValue(),
            "type" => $this->getType(),
            "style" => $this->getVisual()
        ];
        return $deck;
    }

    public function getString()
    {
        return substr(basename($this->imgPath), 0, -4);
    }
}
