<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class VisualCardTest extends TestCase
{
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
}
