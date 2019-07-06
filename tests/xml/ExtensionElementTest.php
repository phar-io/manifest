<?php declare(strict_types = 1);
namespace PharIo\Manifest;

class ExtensionElementTest extends \PHPUnit\Framework\TestCase {
    /** @var ExtensionElement */
    private $extension;

    protected function setUp(): void {
        $dom = new \DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><extension xmlns="https://phar.io/xml/manifest/1.0" for="phar-io/phive" compatible="~0.6" />');
        $this->extension = new ExtensionElement($dom->documentElement);
    }

    public function testNForCanBeRetrieved(): void {
        $this->assertEquals('phar-io/phive', $this->extension->getFor());
    }

    public function testCompatibleVersionConstraintCanBeRetrieved(): void {
        $this->assertEquals('~0.6', $this->extension->getCompatible());
    }
}
