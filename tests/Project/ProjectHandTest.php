<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class ProjectHandTest extends TestCase
{
    public function testCreateVisualCard(): void
    {
        $card = new ProjectHand();
        $this->assertInstanceOf("\App\Project\ProjectHand", $card);
    }


    public function testAddCards(): void
    {
        $cardHand = new ProjectHand();
        $this->assertEquals($cardHand->getCards(), []);
        for ($i = 4; $i < 10; $i++) {
            $card = new VisualCard($i, "Spades");

            $cardHand->addToHand($card);
        }

        $vals = $cardHand->getHand();
        /**
         * @var array{"value": mixed, "type": mixed, "style": mixed} $val
         */
        foreach ($vals as $val) {
            $this->assertArrayHasKey("value", $val);
            $this->assertArrayHasKey("type", $val);
            $this->assertArrayHasKey("style", $val);
        }
    }

    public function testRemoveCard(): void
    {
        $cardHand = new ProjectHand();
        $card = new VisualCard(2, "Spades");
        $faulty = new VisualCard(3, "Diamonds");
        $cardHand->addToHand($card);
        $cardHand->removeFromHand($card);
        $this->assertEquals($cardHand->getCards(), []);
        $this->assertFalse($cardHand->removeFromHand($faulty));
    }

    public function testRemoveJackCard(): void
    {
        $cardHand = new ProjectHand();
        $card = new VisualCard(11, "Spades");
        $faulty = new VisualCard(3, "Diamonds");
        $cardHand->addToHand($card);
        $cardHand->removeFromHand($card);
        $this->assertEquals($cardHand->getCards(), []);
        $this->assertFalse($cardHand->removeFromHand($faulty));
    }

    public function testSortCards(): void
    {
        $cardHand = new ProjectHand();
        for ($i = 4; $i < 10; $i++) {
            $card = new VisualCard(rand(2, 14), "Spades");

            $cardHand->addToHand($card);
        }

        $cardHand->sortByValueDesc();
        $vals = $cardHand->getHand();

        for ($i = 0; $i < count($vals) - 1; $i++) {
            $this->assertLessThanOrEqual($vals[$i]["value"], $vals[$i + 1]["value"]);
        }
    }
}
