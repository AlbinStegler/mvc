<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class VisualCardTest extends TestCase
{
    public function testStringConstruct(): void
    {
        $card = new VisualCard("1_of_spades");
        $this->assertInstanceOf("\App\Project\VisualCard", $card);
        $this->assertEquals($card->getValue(), 1);
        $this->assertEquals($card->getType(), "spades");

        $card = new VisualCard("king_of_spades");
        $this->assertInstanceOf("\App\Project\VisualCard", $card);
        $this->assertEquals($card->getValue(), 13);
        $this->assertEquals($card->getType(), "spades");
    }
    public function testCreateCardGraphic(): void
    {
        $card = new VisualCard(2, "Hearts");
        $this->assertInstanceOf("\App\Project\VisualCard", $card);
    }

    public function testSetStyle(): void
    {
        $card = new VisualCard(13, "Diamonds");
        $this->assertEquals($card->getVisual(), "img/cards/kings/king_of_Diamonds.svg");
    }

    public function testGetAll(): void
    {
        $card = new VisualCard(4, "Diamonds");
        $val = $card->showAll();
        $this->assertArrayHasKey("value", $val);
        $this->assertArrayHasKey("type", $val);
        $this->assertArrayHasKey("style", $val);
    }

    public function testGetAsString(): void
    {
        $card = new VisualCard(4, "Diamonds");
        $this->assertEquals($card->getString(), "4_of_Diamonds");
    }
}