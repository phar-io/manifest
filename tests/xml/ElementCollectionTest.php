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
use PHPUnit\Framework\TestCase;

class ElementCollectionTest extends TestCase {
    public function testEnforcesDOMElementsOnly(): void {
        $dom = new DOMDocument();
        $dom->loadXML('<?xml version="1.0"?><root>text</root>');

        $this->expectException(ElementCollectionException::class);

        new class($dom->documentElement->childNodes) extends ElementCollection {
            public function current(): void {
            }
        };
    }
}
