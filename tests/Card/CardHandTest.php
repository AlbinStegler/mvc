<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardHandTest extends TestCase
{
    public function testCreateCardGraphic() : void
    {
        $card = new CardHand();
        $this->assertInstanceOf("\App\Card\CardHand", $card);
    }

    public function testAddCards() : void
    {
        $cardHand = new CardHand();
        $this->assertEquals($cardHand->getCards(), []);
        for ($i = 4; $i < 10; $i++) {
            $card = new CardGraphic();
            $card->setType("Spades");
            $card->setValue($i);
            $card->setStyle();
            $cardHand->add($card);
        }
        $vals = $cardHand->getCards();

        foreach ($vals as $val) {
            $this->assertArrayHasKey("value", $val);
            $this->assertArrayHasKey("type", $val);
            $this->assertArrayHasKey("style", $val);
        }

    }

    public function testGetSum() : void
    {
        $cardHand = new CardHand();
        for ($i = 4; $i < 10; $i++) {
            $card = new CardGraphic();
            $card->setType("Spades");
            $card->setValue($i);
            $card->setStyle();
            $cardHand->add($card);
        }
        $this->assertEquals($cardHand->getSum(), 39);
    }
}
