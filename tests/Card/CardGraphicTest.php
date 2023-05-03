<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardGraphicTest extends TestCase
{
    public function testCreateCardGraphic() {
        $card = new CardGraphic();
        $this->assertInstanceOf("\App\Card\CardGraphic", $card);
    }

    public function testSetStyle() {
        $card = new CardGraphic();
        $val = $card->getImgPath();
        $this->assertEquals($val, null);
        $card->setValue(3);
        $card->setType("Spades");
        $card->setStyle();
        $val = $card->getImgPath();
        $this->assertEquals($val, "img/cards/threes/3_of_Spades.svg");
        $card->setValue(13);
        $card->setStyle();
        $val = $card->getImgPath();
        $this->assertEquals($val, "img/cards/kings/king_of_Spades.svg");
    }

    public function testShowCard() {
        $card = new CardGraphic();
        $val = $card->showCard();
        $this->assertArrayHasKey("value", $val);
        $this->assertArrayHasKey("type", $val);
        $this->assertArrayHasKey("style", $val);
    }
}