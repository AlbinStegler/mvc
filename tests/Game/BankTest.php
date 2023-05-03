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
    public function testCreateBank() {
        $hand = new BlackjackHand();
        $bank = new Bank($hand);
        $this->assertInstanceOf("\App\Game\Bank", $bank);
    }

    private function createCardGraphic(int $value, string $type) : CardGraphic {
        $c1 = new CardGraphic();
        $c1->setValue($value);
        $c1->setType($type);
        $c1->setStyle();
        return $c1;
    }

    public function testCanIDraw() {
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