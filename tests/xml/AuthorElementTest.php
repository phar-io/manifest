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

class AuthorElementTest extends \PHPUnit\Framework\TestCase {
    /** @var AuthorElement */
    private $author;

    protected function setUp(): void {
        $dom = new DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><author xmlns="https://phar.io/xml/manifest/1.0" name="Reiner Zufall" email="reiner@zufall.de" />');
        $this->author = new AuthorElement($dom->documentElement);
    }

    public function testNameCanBeRetrieved(): void {
        $this->assertEquals('Reiner Zufall', $this->author->getName());
    }

    public function testEmailCanBeRetrieved(): void {
        $this->assertEquals('reiner@zufall.de', $this->author->getEmail());
    }

    public function testHasEmailReturnsTrueWhenEMailIsSet(): void {
        $this->assertTrue($this->author->hasEmail());
    }

    public function testHasEMailReturnsFalseWhenNoEMailAddressIsSet(): void {
        $dom = new DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><author xmlns="https://phar.io/xml/manifest/1.0" name="Reiner Zufall" />');

        $this->assertFalse((new AuthorElement($dom->documentElement))->hasEMail());
    }
}
