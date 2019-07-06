<?php declare(strict_types = 1);
namespace PharIo\Manifest;

use DOMDocument;

class BundlesElementTest extends \PHPUnit\Framework\TestCase {
    /** @var DOMDocument */
    private $dom;

    /** @var BundlesElement */
    private $bundles;

    protected function setUp(): void {
        $this->dom = new DOMDocument();
        $this->dom->loadXML('<?xml version="1.0" ?><bundles xmlns="https://phar.io/xml/manifest/1.0" />');
        $this->bundles = new BundlesElement($this->dom->documentElement);
    }

    public function testThrowsExceptionWhenGetComponentElementsIsCalledButNodesAreMissing(): void {
        $this->expectException(ManifestElementException::class);
        $this->bundles->getComponentElements();
    }

    public function testGetComponentElementsReturnsComponentElementCollection(): void {
        $this->addComponent();
        $this->assertInstanceOf(
            ComponentElement::class,
            $this->bundles->getComponentElements()->current()

        );
    }

    private function addComponent(): void {
        $this->dom->documentElement->appendChild(
            $this->dom->createElementNS('https://phar.io/xml/manifest/1.0', 'component')
        );
    }
}
