<?php declare(strict_types = 1);
namespace PharIo\Manifest;

class AuthorElementTest extends \PHPUnit\Framework\TestCase {
    /** @var AuthorElement */
    private $author;

    protected function setUp(): void {
        $dom = new \DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><author xmlns="https://phar.io/xml/manifest/1.0" name="Reiner Zufall" email="reiner@zufall.de" />');
        $this->author = new AuthorElement($dom->documentElement);
    }

    public function testNameCanBeRetrieved(): void {
        $this->assertEquals('Reiner Zufall', $this->author->getName());
    }

    public function testEmailCanBeRetrieved(): void {
        $this->assertEquals('reiner@zufall.de', $this->author->getEmail());
    }
}
