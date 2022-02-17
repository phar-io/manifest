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

class AuthorElementCollectionTest extends \PHPUnit\Framework\TestCase {
    public function testAuthorElementCanBeRetrievedFromCollection(): void {
        $dom = new DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><author xmlns="https://phar.io/xml/manifest/1.0" name="Reiner Zufall" email="reiner@zufall.de" />');
        $collection = new AuthorElementCollection($dom->childNodes);

        foreach ($collection as $authorElement) {
            $this->assertInstanceOf(AuthorElement::class, $authorElement);
        }
    }
}
