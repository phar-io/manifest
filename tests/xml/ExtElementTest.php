<?php declare(strict_types = 1);
namespace PharIo\Manifest;

class ExtElementTest extends \PHPUnit\Framework\TestCase {
    /** @var ExtElement */
    private $ext;

    protected function setUp(): void {
        $dom = new \DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><ext xmlns="https://phar.io/xml/manifest/1.0" name="dom" />');
        $this->ext = new ExtElement($dom->documentElement);
    }

    public function testNameCanBeRetrieved(): void {
        $this->assertEquals('dom', $this->ext->getName());
    }
}
