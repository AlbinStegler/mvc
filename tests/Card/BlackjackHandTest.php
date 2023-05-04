<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackjackHand.
 */
class BlackjackHandTest extends TestCase
{
    public function testCreateBlackjackHand()
    {
        $bHand = new BlackjackHand();
        $this->assertInstanceOf("\App\Card\BlackjackHand", $bHand);
    }

    private function createCardGraphic(int $value, string $type): CardGraphic
    {
        $c1 = new CardGraphic();
        $c1->setValue($value);
        $c1->setType($type);
        $c1->setStyle();
        return $c1;
    }

    public function testPlayerWon()
    {
        $player = new BlackjackHand();
        $bank = new BlackjackHand();

        $c1 = $this->createCardGraphic(10, "Spades");
        $c2 = $this->createCardGraphic(10, "Diamonds");

        $player->add($c1);
        $player->add($c2);

        $c3 = $this->createCardGraphic(14, "Spades");
        $c4 = $this->createCardGraphic(11, "Diamonds");
        $bank->add($c3);
        $bank->add($c4);

        $c5 = $this->createCardGraphic(8, "Spades");
        $c6 = $this->createCardGraphic(10, "Diamonds");

        $bank2 = new BlackjackHand();
        $bank2->add($c5);
        $bank2->add($c6);

        $this->assertTrue($player->playerWon($player, $bank2));
    }

    public function testLowerPoints()
    {
        $player = new BlackjackHand();
        $c1 = $this->createCardGraphic(14, "Spades");
        $c2 = $this->createCardGraphic(12, "Diamonds");
        $c3 = $this->createCardGraphic(8, "Diamonds");



        $player->add($c1);
        $player->add($c1);
        $this->assertEquals($player->getSum(), 12);
        $player->add($c2);
        $player->add($c2);
        $player->add($c1);
        $this->assertEquals($player->getSum(), 23);

    }
}
