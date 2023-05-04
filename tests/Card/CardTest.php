<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    public function testCreateCard() : void
    {
        $card = new Card();
        $this->assertInstanceOf("\App\Card\Card", $card);

    }

    public function testSetValue() : void
    {
        $card = new Card();
        $val = $card->getValue();
        $this->assertEquals($val, null);
        $card->setValue(3);
        $val = $card->getValue();
        $this->assertEquals($val, 3);
    }

    public function testSetType() : void
    {
        $card = new Card();
        $val = $card->getType();
        $this->assertEquals($val, null);
        $card->setType("Spades");
        $val = $card->getType();
        $this->assertEquals($val, "Spades");
    }

    public function testShowCard() : void
    {
        $card = new Card();
        $val = $card->showCard();
        $this->assertArrayHasKey("value", $val);
        $this->assertArrayHasKey("type", $val);
    }
}
