<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

class ProjectFiveHandRulesTest extends TestCase
{
    public function testFiveHandRule(): void
    {
        $hand = new ProjectHand();
        $rule = new FiveHandRules($hand);
        $this->assertInstanceOf("\App\Project\FiveHandRules", $rule);
    }

    private function createHand(array $values): ProjectHand
    {
        $hand = new ProjectHand();
        foreach ($values as $val) {
            $card = new VisualCard($val[0], $val[1]);
            $hand->addToHand($card);
        }

        return $hand;
    }

    public function testPair(): void
    {

        $hand = $this->createHand([
            [2, "hearts"],
            [2, "spades"],
            [10, "clubs"],
            [12, "hearts"],
            [6, "diamonds"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("pair", $rule->getRule());
    }

    public function testTwoPair(): void
    {

        $hand = $this->createHand([
            [2, "hearts"],
            [2, "spades"],
            [10, "clubs"],
            [6, "hearts"],
            [6, "diamonds"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("two_pair", $rule->getRule());
    }

    public function testThreeOfAKind(): void
    {

        $hand = $this->createHand([
            [2, "hearts"],
            [2, "spades"],
            [2, "diamonds"],
            [8, "hearts"],
            [6, "diamonds"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("three_of_a_kind", $rule->getRule());
    }

    public function testFourOfAKind(): void
    {

        $hand = $this->createHand([
            [2, "hearts"],
            [2, "spades"],
            [2, "diamonds"],
            [2, "clubs"],
            [6, "diamonds"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("four_of_a_kind", $rule->getRule());
    }

    public function testStraight(): void
    {

        $hand = $this->createHand([
            [2, "hearts"],
            [3, "spades"],
            [4, "diamonds"],
            [5, "clubs"],
            [6, "diamonds"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("straight", $rule->getRule());
    }

    public function testMiddleStraight(): void
    {

        $hand = $this->createHand([
            [6, "hearts"],
            [7, "spades"],
            [8, "diamonds"],
            [9, "clubs"],
            [10, "diamonds"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("straight", $rule->getRule());
    }

    public function testHighStraight(): void
    {

        $hand = $this->createHand([
            [10, "hearts"],
            [11, "spades"],
            [12, "diamonds"],
            [13, "clubs"],
            [14, "diamonds"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("straight", $rule->getRule());
    }

    public function testFlush(): void
    {

        $hand = $this->createHand([
            [2, "hearts"],
            [5, "hearts"],
            [12, "hearts"],
            [7, "hearts"],
            [6, "hearts"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("flush", $rule->getRule());
    }

    public function testStraightFlush(): void
    {

        $hand = $this->createHand([
            [2, "hearts"],
            [3, "hearts"],
            [4, "hearts"],
            [5, "hearts"],
            [6, "hearts"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("straight_flush", $rule->getRule());
    }

    public function testMiddleFlush(): void
    {

        $hand = $this->createHand([
            [6, "hearts"],
            [7, "hearts"],
            [8, "hearts"],
            [9, "hearts"],
            [10, "hearts"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("straight_flush", $rule->getRule());
    }

    public function testRoyalFlush(): void
    {

        $hand = $this->createHand([
            [10, "hearts"],
            [11, "hearts"],
            [12, "hearts"],
            [13, "hearts"],
            [14, "hearts"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("royal_flush", $rule->getRule());
    }

    public function testFullHouse(): void
    {

        $hand = $this->createHand([
            [10, "hearts"],
            [10, "diamonds"],
            [10, "clubs"],
            [4, "hearts"],
            [4, "spades"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals("full_house", $rule->getRule());
    }

    public function testNoPointsHighCard(): void
    {
        $hand = $this->createHand([
            [11, "diamonds"],
            [12, "hearts"],
            [8, "clubs"],
            [3, "hearts"],
            [4, "spades"]
        ]);
        $card = new VisualCard(12, "hearts");
        $rule = new FiveHandRules($hand);
        $this->assertEquals($rule->getHighCard()[0], $card->showAll());
    }

    public function testPairHighCard(): void
    {
        $hand = $this->createHand([
            [11, "diamonds"],
            [11, "hearts"],
            [8, "clubs"],
            [3, "hearts"],
            [4, "spades"]
        ]);
        $returnHand = $this->createHand([
            [11, "diamonds"],
            [11, "hearts"],
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals($rule->getHighCard(), $returnHand->getCards());
    }

    public function testThreeOfAKindHighCard(): void
    {
        $hand = $this->createHand([
            [8, "diamonds"],
            [8, "hearts"],
            [11, "clubs"],
            [8, "clubs"],
            [4, "spades"]
        ]);
        $returnHand = $this->createHand([
            [8, "clubs"],
            [8, "diamonds"],
            [8, "hearts"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals($rule->getHighCard(), $returnHand->getCards());
    }

    public function testFourOfAKindHighCard(): void
    {
        $hand = $this->createHand([
            [8, "diamonds"],
            [8, "hearts"],
            [11, "clubs"],
            [8, "clubs"],
            [8, "spades"]
        ]);
        $returnHand = $this->createHand([
            [8, "clubs"],
            [8, "diamonds"],
            [8, "hearts"],
            [8, "spades"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals($rule->getHighCard(), $returnHand->getCards());
    }

    public function testFourOfAKindHighCardLowCardLast(): void
    {
        $hand = $this->createHand([
            [8, "diamonds"],
            [8, "hearts"],
            [7, "clubs"],
            [8, "clubs"],
            [8, "spades"]
        ]);
        $returnHand = $this->createHand([
            [8, "clubs"],
            [8, "diamonds"],
            [8, "hearts"],
            [8, "spades"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals($rule->getHighCard(), $returnHand->getCards());
    }

    public function testStraightHighCard(): void
    {
        $hand = $this->createHand([
            [2, "diamonds"],
            [3, "hearts"],
            [4, "clubs"],
            [5, "clubs"],
            [6, "spades"]
        ]);
        $returnHand = $this->createHand([
            [6, "spades"],
            [5, "clubs"],
            [4, "clubs"],
            [3, "hearts"],
            [2, "diamonds"]
        ]);
        $rule = new FiveHandRules($hand);
        $this->assertEquals($rule->getHighCard(), $returnHand->getCards());
    }

    public function testWinnerPairs(): void
    {
        $hand2 = $this->createHand([
            [4, "hearts"],
            [4, "clubs"],
            [5, "clubs"],
            [6, "spades"],
            [7, "diamonds"],
        ]);

        $hand1 = $this->createHand([
            [2, "diamonds"],
            [4, "diamonds"],
            [4, "spades"],
            [5, "clubs"],
            [6, "spades"]
        ]);

        $rule1 = new FiveHandRules($hand1);
        $rule2 = new FiveHandRules($hand2);
        $this->assertTrue($rule1->won($rule2));
        $this->assertFalse($rule2->won($rule1));
    }

    public function testWinnerDifferentRules(): void
    {
        $hand1 = $this->createHand([
            [4, "hearts"],
            [4, "clubs"],
            [5, "clubs"],
            [6, "spades"],
            [7, "diamonds"],
        ]);

        $hand2 = $this->createHand([
            [3, "diamonds"],
            [4, "diamonds"],
            [5, "spades"],
            [6, "clubs"],
            [7, "spades"]
        ]);

        $rule1 = new FiveHandRules($hand1);
        $rule2 = new FiveHandRules($hand2);

        $this->assertFalse($rule1->won($rule2));
        $this->assertTrue($rule2->won($rule1));
    }

    public function testWinnerSameRuleDifferentValues(): void
    {
        $hand1 = $this->createHand([
            [4, "hearts"],
            [4, "clubs"],
            [4, "diamonds"],
            [6, "spades"],
            [7, "diamonds"],
        ]);

        $hand2 = $this->createHand([
            [3, "diamonds"],
            [6, "diamonds"],
            [6, "spades"],
            [6, "clubs"],
            [7, "spades"]
        ]);

        $rule1 = new FiveHandRules($hand1);
        $rule2 = new FiveHandRules($hand2);

        $this->assertFalse($rule1->won($rule2));
        $this->assertTrue($rule2->won($rule1));
    }
}