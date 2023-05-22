<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class BookTest extends TestCase
{
    public function testCreateBook(): void
    {
        $book = new Book();
        $this->assertInstanceOf("\App\Entity\Book", $book);
    }

    public function testId(): void
    {
        $book = new Book();
        $val = $book->getId();
        $this->assertEquals($val, null);
    }

    public function testTitle(): void
    {
        $book = new Book();
        $val = $book->getTitle();
        $this->assertEquals($val, null);
        $book->setTitle("TEST");
        $val = $book->getTitle();
        $this->assertEquals($val, "TEST");
    }

    public function testGetSetISbn(): void
    {
        $book = new Book();
        $val = $book->getIsbn();
        $this->assertEquals($val, null);
        $book->setIsbn(7126390767510);
        $val = $book->getIsbn();
        $this->assertEquals($val, 7126390767510);
    }

    public function testGetSetAuthor(): void
    {
        $book = new Book();
        $val = $book->getAuthor();
        $this->assertEquals($val, null);
        $book->setAuthor("Me");
        $val = $book->getAuthor();
        $this->assertEquals($val, "Me");
    }

    public function testGetSetImg(): void
    {
        $book = new Book();
        $val = $book->getImg();
        $this->assertEquals($val, null);
        $book->setImg("img/img.png");
        $val = $book->getImg();
        $this->assertEquals($val, "img/img.png");
    }
}
