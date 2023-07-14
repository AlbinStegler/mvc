<?php

namespace App\Project;

use App\Card\CardHand;

class FiveHandRules
{
    protected $highCard = [];
    protected $rule = "not_set";
    protected $hand;

    public function __construct(ProjectHand $cardHand)
    {
        $this->hand = $cardHand;
        $this->setRule();
        $this->setHighCards();
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function getHighCard()
    {
        return $this->highCard;
    }

    public function setRule()
    {
        if (empty($this->hand->getCards())) {
            return "No cards in hand";
        }
        $alternatives = [
            "pair",
            "two_pair",
            "three_of_a_kind",
            "four_of_a_kind",
            "full_house",
            "flush",
            "straight",
            "straight_flush",
            "royal_flush"
        ];

        //Check for same values if its a pair or three of a kind it cant be a flush or royalflush
        $this->rule = $this->checkSameValue();
        //TVÅ PAR
        if ($this->rule == "pair") {
            //Kolla för 2 par returnera om det är det
            $this->rule = $this->twoPairs();
            if ($this->rule == "two_pair") {
                return $this->rule;
            }
            $this->rule = "pair";
            return $this->rule;
        }
        if ($this->rule == "three_of_a_kind") {
            //Check for full house
            if ($this->fullHouse()) {
                $this->rule = "full_house";
                return $this->rule;
            }
            return "three_of_a_kind";
        }

        if ($this->rule == "no matching cards") {

            if ($this->royalFlush() == "royal_flush") {
                $this->rule = "royal_flush";
                return $this->rule;
            }
            // Om båda är sanna är det en StraightFlush
            if ($this->straight() && $this->flush()) {
                $this->rule = "straight_flush";
                return $this->rule;
            }
            // Kolla stege
            if ($this->straight()) {
                $this->rule = "straight";
                return $this->rule;
            }
            // Kolla färg
            if ($this->flush()) {
                $this->rule = "flush";
                return $this->rule;
            }
        }
        return "no_points";
    }

    private function twoPairs()
    {
        $cards = $this->hand->getCards();

        $values = [];

        foreach ($cards as $card) {
            $values[] = $card->getValue();
        }

        $count = array();
        foreach ($values as $element) {
            if (!isset($count[$element])) {
                $count[$element] = 1;
            } else {
                $count[$element]++;
            }
        }

        $twiceCount = 0;
        foreach ($count as $appearanceCount) {
            if ($appearanceCount == 2) {
                $twiceCount++;
            }
        }
        if ($twiceCount == 2) {
            return "two_pair";
        }
        return "none";
    }

    private function fullHouse()
    {
        $values = $this->hand->getCards();
        $temp = [];

        foreach ($values as $val) {
            $temp[] = $val->getValue();
        }
        $times = array_count_values($temp);

        $total = 0;
        foreach ($times as $time => $value) {
            $total += $value;
        }

        if (count($times) == 2 && $total == 5) {
            return true;
        }

        return false;
    }

    private function straight()
    {
        $cards = $this->hand->getCards();
        $values = [];
        foreach ($cards as $card) {
            $values[] = $card->getValue();
        }
        sort($values);
        for ($i = 0; $i < count($values) - 1; $i++) {
            if ($values[$i + 1] - $values[$i] > 1) {
                return false;
            }
        }
        return true;
    }

    private function flush()
    {
        $cards = $this->hand->getHand();
        $type = $cards[0]["type"];
        foreach ($cards as $card) {
            if ($card["type"] != $type) {
                return false;
            }
        }
        return true;
    }

    private function checkSameValue(): string
    {
        $values = $this->hand->getCards();
        $temp = [];

        foreach ($values as $val) {
            $temp[] = $val->getValue();
        }
        $times = array_count_values($temp);

        $current = 0;
        foreach ($times as $time => $value) {

            if ($value > $current) {
                $current = $value;
            }
        }
        if ($current == 4) {
            return "four_of_a_kind";
        }
        if ($current == 3) {
            return "three_of_a_kind";
        }
        if ($current == 2) {
            return "pair";
        }

        return "no matching cards";
    }

    private function royalFlush(): string
    {
        $cards = $this->hand->getHand();
        $type = $cards[0]["type"];
        $values = [10, 11, 12, 13, 14];
        $sorted = [];
        foreach ($cards as $card) {
            if ($card["type"] != $type) {
                return false;
            }
            $sorted[] = $card["value"];
        }

        sort($sorted);

        if ($sorted == $values) {
            return "royal_flush";
        }
        return "none";
    }

    private function setSameValueHighCard($cards): bool
    {
        if ($this->rule == "pair") {
            $separetedCards = [];
            for ($i = 0; $i < count($cards) - 1; $i++) {
                if ($cards[$i]->getValue() == $cards[$i + 1]->getValue()) {
                    $separetedCards[] = $cards[$i];
                    $separetedCards[] = $cards[$i + 1];
                    break;
                }
            }
            $this->highCard = $separetedCards;
            return true;
        }

        if ($this->rule == "three_of_a_kind") {
            $separetedCards = [];
            for ($i = 0; $i < count($cards) - 1; $i++) {
                if ($cards[$i]->getValue() == $cards[$i + 1]->getValue()) {

                    $separetedCards[] = $cards[$i];
                    $separetedCards[] = $cards[$i + 1];
                    $separetedCards[] = $cards[$i + 2];
                    break;
                }
            }
            $this->highCard = $separetedCards;
            return true;
        }

        if ($this->rule == "four_of_a_kind") {
            $separetedCards = [];
            if ($cards[0]->getValue() == $cards[1]->getValue()) {
                $separetedCards[] = $cards[0];
                $separetedCards[] = $cards[1];
                $separetedCards[] = $cards[2];
                $separetedCards[] = $cards[3];
                $this->highCard = $separetedCards;
                return true;
            }
            $separetedCards[] = $cards[1];
            $separetedCards[] = $cards[2];
            $separetedCards[] = $cards[3];
            $separetedCards[] = $cards[4];
            $this->highCard = $separetedCards;
            return true;
        }
        return false;
    }

    private function setHighCards(): bool
    {
        //Sorterar i storleksordning
        $this->hand->sortByValueDesc();
        $cards = $this->hand->getCards();

        //Kollar lättaste möjligheterna först
        if ($this->rule == "no matching cards") {
            $this->highCard = $this->hand->getHand();
            return true;
        }

        //Om det är någon av dessa är de sorterade på värde alla ska hanteras likadant
        $same = [
            "full_house",
            "flush",
            "straight",
            "straight_flush",
            "royal_flush"
        ];
        if (in_array($this->rule, $same)) {
            $this->highCard = $cards;
            return true;
        }

        //Om det är samma värden på korten kollas villkor här
        if ($this->setSameValueHighCard($cards)) {
            return true;
        };

        //Inga kort kunde sättas kanske tom hand ?
        return false;
    }

    public function getColorValue(): int
    {
        $cards = $this->getHighCard();
        $valueOrder = ["spades" => 4, "hearts" => 3, "clubs" => 2, "diamonds" => 1];
        $value = -1;

        foreach ($cards as $card) {
            if ($value < $valueOrder[$card->getType()]) {
                $value = $valueOrder[$card->getType()];
            }
        }
        return $value;
    }

    private function getRulePoints(): int
    {
        $alternatives = [
            "no matching cards" => 0,
            "pair" => 1,
            "two_pair" => 2,
            "three_of_a_kind" => 3,
            "four_of_a_kind" => 4,
            "full_house" => 5,
            "flush" => 6,
            "straight" => 7,
            "straight_flush" => 8,
            "royal_flush" => 9
        ];

        return $alternatives[$this->getRule()];
        ;
    }

    /**
     * returns true if winner is the hand calling function
     */
    public function won(FiveHandRules $otherRule): bool
    {

        $selfHand = $this->hand->getCards();

        //Jämför om båda har samma regler vinner den med högst kort
        if ($otherRule->getRule() == $this->rule) {
            //Om ena har ett högre värde vinner den spelaren
            if ($otherRule->highCard[0]->getValue() > $this->highCard[0]->getValue()) {
                return false;
            }
            //Om de har samma kort jämförs valören på korten
            if ($otherRule->highCard[0]->getValue() == $this->highCard[0]->getValue()) {
                if ($otherRule->getColorValue() > $this->getColorValue()) {
                    return false;
                }
            }

            return true;
        }

        $points = $this->getRulePoints();
        $pointsComparison = $otherRule->getRulePoints();

        if ($pointsComparison > $points) {
            return false;
        }
        return true;
    }
}
