<?php

namespace App\Game;
use App\Card\BlackjackHand;
use App\Card\CardGraphic;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackjackHand.
 */
class PlayerTest extends TestCase
{
    public function testCreatePlayer() {
        $hand = new BlackjackHand();
        $player = new Player($hand);
        $this->assertInstanceOf("\App\Game\Player", $player);
    }

    public function testPlayerBlackjack() {
        $cardHand = new BlackjackHand();
        $this->assertEquals($cardHand->getCards(), []);
        
        $sum = $cardHand->getSum();
        $bank = new Player($cardHand);
        $this->assertFalse($bank->canIDraw($sum));
    }

}