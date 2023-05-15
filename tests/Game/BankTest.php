<?php

namespace App\Game;

use App\Card\BlackjackHand;
use App\Card\CardGraphic;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackjackHand.
 */
class BankTest extends TestCase
{
    public function testCreateBank(): void
    {
        $hand = new BlackjackHand();
        $bank = new Bank($hand);
        $this->assertInstanceOf("\App\Game\Bank", $bank);
    }

    public function testCanIDraw(): void
    {
        $cardHand = new BlackjackHand();
        $this->assertEquals($cardHand->getCards(), []);
        for ($i = 4; $i < 10; $i++) {
            $card = new CardGraphic();
            $card->setType("Spades");
            $card->setValue($i);
            $card->setStyle();
            $cardHand->add($card);
        }
        $sum = $cardHand->getSum();
        $bank = new Bank($cardHand);
        $this->assertFalse($bank->canIDraw($sum));

        $cardHand = new BlackjackHand();
        $bank = new Bank($cardHand);
        $sum = $cardHand->getSum();
        $this->assertTrue($bank->canIDraw($sum));

    }
}
