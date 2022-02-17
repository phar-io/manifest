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

class ExtensionElementTest extends \PHPUnit\Framework\TestCase {
    /** @var ExtensionElement */
    private $extension;

    protected function setUp(): void {
        $dom = new DOMDocument();
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
