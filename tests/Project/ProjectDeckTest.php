<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

class ProjectDeckTest extends TestCase
{
    public function testCreateDeckOfCards(): void
    {
        $card = new ProjectDeck();
        $this->assertInstanceOf("\App\Project\ProjectDeck", $card);
    }

    public function testSetupDeck(): void
    {
        $card = new ProjectDeck();
        $card->setupDeck();

        $num = $card->getDeckSize();
        $this->assertEquals($num, 52);

        $cards = $card->getAsArray();
        $colors = ["clubs", "hearts", "spades", "diamonds"];
        $values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $times1 = 0;
        $times2 = 0;

        foreach ($cards as $c) {
            /**
             * @var array{value: int, type: string, style: string} $c
             */
            $this->assertArrayHasKey("type", $c);
            $this->assertArrayHasKey("value", $c);
            $this->assertArrayHasKey("style", $c);

            $this->assertContains($colors[$times1], $c);
            $this->assertEquals($values[$times2], $c["value"]);

            if ($times1 < 3) {
                $times1++;
            } elseif ($times1 == 3) {
                $times1 = 0;
                $times2++;
            }
        }
    }

    public function testRecreateDeck(): void
    {
        $deck = new ProjectDeck();
        $deck->setupDeck();
        $cardToRemove = new VisualCard(8, "diamonds");



        $this->assertTrue($deck->removeCard($cardToRemove));
        $this->assertEquals($deck->getDeckSize(), 51);

        $deck2 = new ProjectDeck();
        $deck2->recreateDeck([$cardToRemove]);
        $this->assertTrue($deck2->equal($deck));
    }

    public function testRemoveNotExist(): void
    {
        $deck = new ProjectDeck();
        $this->assertFalse($deck->removeCard(new VisualCard(2, "hearts")));
    }

    public function testShuffleDeck(): void
    {
        $card1 = new ProjectDeck();
        $card1->setupDeck();
        $card1->shuffleDeck();
        $card2 = new ProjectDeck();
        $card2->setupDeck();
        $this->assertFalse($card1->equal($card2));
    }

    public function testDrawCard(): void
    {
        $deck = new ProjectDeck();
        $deck->setupDeck();
        $deck->shuffleDeck();
        $arr = $deck->getAsArray();
        $card = array_shift($arr);

        $this->assertEquals($card, $deck->drawCard()->showAll());

        $deck2 = new ProjectDeck();
        $this->assertEquals($deck2->drawCard(), new VisualCard(2, "No cards"));
    }
}
