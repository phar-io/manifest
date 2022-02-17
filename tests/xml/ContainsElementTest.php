<?php declare(strict_types = 1);
/*
 * This file is part of PharIo\Manifest.
 *
 * Copyright (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de> and contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace PharIo\Manifest;

use DOMDocument;
use DOMElement;

class ContainsElementTest extends \PHPUnit\Framework\TestCase {
    /** @var DOMElement */
    private $domElement;

    /** @var ContainsElement */
    private $contains;

    protected function setUp(): void {
        $dom = new DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><php xmlns="https://phar.io/xml/manifest/1.0" name="phpunit/phpunit" version="5.6.5" type="application" />');
        $this->domElement = $dom->documentElement;
        $this->contains   = new ContainsElement($this->domElement);
    }

    public function testVersionCanBeRetrieved(): void {
        $this->assertEquals('5.6.5', $this->contains->getVersion());
    }

    public function testThrowsExceptionWhenVersionAttributeIsMissing(): void {
        $this->domElement->removeAttribute('version');
        $this->expectException(ManifestElementException::class);
        $this->contains->getVersion();
    }

    public function testNameCanBeRetrieved(): void {
        $this->assertEquals('phpunit/phpunit', $this->contains->getName());
    }

    public function testThrowsExceptionWhenNameAttributeIsMissing(): void {
        $this->domElement->removeAttribute('name');
        $this->expectException(ManifestElementException::class);
        $this->contains->getName();
    }

    public function testTypeCanBeRetrieved(): void {
        $this->assertEquals('application', $this->contains->getType());
    }

    public function testThrowsExceptionWhenTypeAttributeIsMissing(): void {
        $this->domElement->removeAttribute('type');
        $this->expectException(ManifestElementException::class);
        $this->contains->getType();
    }

    public function testGetExtensionElementReturnsExtensionElement(): void {
        $this->domElement->appendChild(
            $this->domElement->ownerDocument->createElementNS('https://phar.io/xml/manifest/1.0', 'extension')
        );
        $this->assertInstanceOf(ExtensionElement::class, $this->contains->getExtensionElement());
    }
}
