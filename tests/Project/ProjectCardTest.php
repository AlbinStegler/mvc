<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class ProjectCardTest extends TestCase
{
    public function testCreateCard(): void
    {
        $card = new ProjectCard(1, "Spades");
        $this->assertInstanceOf("\App\Project\ProjectCard", $card);
    }

    public function testGetValue(): void
    {
        $card = new ProjectCard(13, "Diamonds");
        $val = $card->getValue();
        $this->assertEquals($val, 13);
    }

    public function testGetType(): void
    {
        $card = new ProjectCard(3, "Hearts");
        $val = $card->getType();
        $this->assertEquals($val, "Hearts");
    }

    public function testGetAll(): void
    {
        $card = new ProjectCard(5, "Diamonds");
        $val = $card->getAll();
        $this->assertArrayHasKey("value", $val);
        $this->assertArrayHasKey("type", $val);
    }
}
