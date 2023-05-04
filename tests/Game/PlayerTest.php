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
    public function testCreatePlayer() : void
    {
        $hand = new BlackjackHand();
        $player = new Player($hand);
        $this->assertInstanceOf("\App\Game\Player", $player);
    }

    public function testDraw() : void
    {
        $cardHand = new BlackjackHand();
        $this->assertEquals($cardHand->getCards(), []);

        $sum = $cardHand->getSum();
        $player = new Player($cardHand);
        $this->assertTrue($player->canIDraw($sum));
        $this->assertFalse($player->canIDraw(21));
    }

    private function createCardGraphic(int $value, string $type) : CardGraphic
    {
        $card = new CardGraphic();
        $card->setValue($value);
        $card->setType($type);
        $card->setStyle();
        return $card;
    }

    public function testPlayerWon() : void
    {
        $blackjack = new BlackjackHand();
        $ten = $this->createCardGraphic(10, "Diamonds");
        $ace = $this->createCardGraphic(14, "Diamonds");
        $blackjack->add($ten);
        $blackjack->add($ace);

        $player = new Player($blackjack);
        $bank = new BlackjackHand();
        //Spelare vunnit
        $this->assertTrue($player->playerWon($bank));
        $bank->add($ten);
        $bank->add($ace);
        //Spelare förlorat
        $this->assertFalse($player->playerWon($bank));

        $blackjack->removeCard($ace);
        $blackjack->add($ten);

        $bank->removeCard($ace);
        $bank->add($ten);
        //Spelare förlorat
        $this->assertFalse($player->playerWon($bank));
        $blackjack->removeCard($ten);
        $this->assertFalse($player->playerWon($bank));
        $blackjack->add($ten);
        $bank->removeCard($ten);
        $this->assertTrue($player->playerWon($bank));
    }

}
