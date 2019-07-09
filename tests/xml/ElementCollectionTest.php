<?php declare(strict_types = 1);
namespace PharIo\Manifest;

use DOMDocument;
use PHPUnit\Framework\TestCase;

class ElementCollectionTest extends TestCase {
    public function testEnforcesDOMElementsOnly(): void {
        $dom = new DOMDocument();
        $dom->loadXML('<?xml version="1.0"?><root>text</root>');

        $this->expectException(ElementCollectionException::class);

        new class($dom->documentElement->childNodes) extends ElementCollection {
            public function current() {}
        };
    }
}
