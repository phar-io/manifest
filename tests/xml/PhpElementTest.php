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

class PhpElementTest extends \PHPUnit\Framework\TestCase {
    /** @var DOMDocument */
    private $dom;

    /** @var PhpElement */
    private $php;

    protected function setUp(): void {
        $this->dom = new DOMDocument();
        $this->dom->loadXML('<?xml version="1.0" ?><php xmlns="https://phar.io/xml/manifest/1.0" version="^5.6 || ^7.0" />');
        $this->php = new PhpElement($this->dom->documentElement);
    }

    public function testVersionConstraintCanBeRetrieved(): void {
        $this->assertEquals('^5.6 || ^7.0', $this->php->getVersion());
    }

    public function testHasExtElementsReturnsFalseWhenNoExtensionsAreRequired(): void {
        $this->assertFalse($this->php->hasExtElements());
    }

    public function testHasExtElementsReturnsTrueWhenExtensionsAreRequired(): void {
        $this->addExtElement();
        $this->assertTrue($this->php->hasExtElements());
    }

    public function testGetExtElementsReturnsExtElementCollection(): void {
        $this->addExtElement();
        $this->assertInstanceOf(ExtElementCollection::class, $this->php->getExtElements());
    }

    private function addExtElement(): void {
        $this->dom->documentElement->appendChild(
            $this->dom->createElementNS('https://phar.io/xml/manifest/1.0', 'ext')
        );
    }
}
