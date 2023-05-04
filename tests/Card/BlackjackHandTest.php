<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackjackHand.
 */
class BlackjackHandTest extends TestCase
{
    public function testCreateBlackjackHand() : void
    {
        $bHand = new BlackjackHand();
        $this->assertInstanceOf("\App\Card\BlackjackHand", $bHand);
    }

    private function createCardGraphic(int $value, string $type): CardGraphic
    {
        $card = new CardGraphic();
        $card->setValue($value);
        $card->setType($type);
        $card->setStyle();
        return $card;
    }

    public function testPlayerWon() : void
    {
        $player = new BlackjackHand();
        $bank = new BlackjackHand();

        $card1 = $this->createCardGraphic(10, "Spades");
        $card2 = $this->createCardGraphic(10, "Diamonds");

        $player->add($card1);
        $player->add($card2);

        $card3 = $this->createCardGraphic(14, "Spades");
        $card4 = $this->createCardGraphic(11, "Diamonds");
        $bank->add($card3);
        $bank->add($card4);

        $card5 = $this->createCardGraphic(8, "Spades");
        $card6 = $this->createCardGraphic(10, "Diamonds");

        $bank2 = new BlackjackHand();
        $bank2->add($card5);
        $bank2->add($card6);

        $this->assertTrue($player->playerWon($player, $bank2));
    }

    public function testLowerPoints() : void
    {
        $player = new BlackjackHand();
        $card1 = $this->createCardGraphic(14, "Spades");
        $card2 = $this->createCardGraphic(12, "Diamonds");

        $player->add($card1);
        $player->add($card1);
        $this->assertEquals($player->getSum(), 12);
        $player->add($card2);
        $player->add($card2);
        $player->add($card1);
        $this->assertEquals($player->getSum(), 23);
    }

    public function testGetHand() : void {
        $player = new BlackjackHand();
        $card1 = $this->createCardGraphic(14, "Spades");
        $card2 = $this->createCardGraphic(12, "Diamonds");

        $player->add($card1);
        $player->add($card2);
        
        $hand = $player->getHand();

        $copy = [$card1, $card2];

        $this->assertEquals($hand, $copy);
    }

    public function testMerge() : void {
        $player = new BlackjackHand();
        $player2 = new BlackjackHand();

        $card1 = $this->createCardGraphic(14, "Spades");
        $card2 = $this->createCardGraphic(12, "Diamonds");

        $player->add($card1);
        $player->add($card2);

        $cards = [$card1, $card2];

        $player2->mergeCards($cards);
        $this->assertEquals($player2, $player);
    }
}
