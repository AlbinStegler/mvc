<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackjackHand.
 */
class DeckOfCardsTest extends TestCase
{
    public function testCreateDeckOfCards(): void
    {
        $card = new DeckOfCards();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $card);
    }

    public function testSetupDeck(): void
    {
        $card = new DeckOfCards();
        $card->setupDeck();

        $num = $card->getDeckSize();
        $this->assertEquals($num, 52);

        $cards = $card->showDeck();
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
        $card = new DeckOfCards();
        $card->setupDeck();
        $cardToRemove = new CardGraphic();
        $cardToRemove->setValue(8);
        $cardToRemove->setType("diamonds");
        $cardToRemove->setStyle();

        $card->removeCard($cardToRemove);

        $this->assertFalse(array_search($cardToRemove->showCard(), $card->showDeck()));
        $this->assertEquals($card->getDeckSize(), 51);

        $deck2 = new DeckOfCards();
        $deck2->recreateDeck([$cardToRemove]);
        $this->assertTrue($deck2->equal($card));
    }

    public function testShuffleDeck(): void
    {
        $card1 = new DeckOfCards();
        $card1->setupDeck();
        $card1->shuffleDeck();
        $card2 = new DeckOfCards();
        $card2->setupDeck();
        $this->assertFalse($card1->equal($card2));
    }

    public function testDrawCard(): void
    {
        $card1 = new DeckOfCards();
        $card1->setupDeck();
        $card1->shuffleDeck();
        $arr = $card1->showDeck();
        $card = array_shift($arr);

        $this->assertEquals($card, $card1->drawCard()->showCard());
    }
}