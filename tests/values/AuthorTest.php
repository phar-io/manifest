<?php declare(strict_types = 1);
/*
 * This file is part of PharIo\Manifest.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PharIo\Manifest;

use PHPUnit\Framework\TestCase;

/**
 * @covers PharIo\Manifest\Author
 *
 * @uses PharIo\Manifest\Email
 */
class AuthorTest extends TestCase {
    /** @var Author */
    private $author;

    protected function setUp(): void {
        $this->author = new Author('Joe Developer', new Email('user@example.com'));
    }

    public function testCanBeCreated(): void {
        $this->assertInstanceOf(Author::class, $this->author);
    }

    public function testNameCanBeRetrieved(): void {
        $this->assertEquals('Joe Developer', $this->author->getName());
    }

    public function testEmailCanBeRetrieved(): void {
        $email = $this->author->getEmail();
        $this->assertEquals('user@example.com', $email->asString());
    }

    public function testCanBeUsedAsString(): void {
        $this->assertEquals('Joe Developer <user@example.com>', $this->author->asString());
    }
}
