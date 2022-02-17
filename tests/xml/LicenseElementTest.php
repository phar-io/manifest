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

class LicenseElementTest extends \PHPUnit\Framework\TestCase {
    /** @var LicenseElement */
    private $license;

    protected function setUp(): void {
        $dom = new DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><license xmlns="https://phar.io/xml/manifest/1.0" type="BSD-3" url="https://some.tld/LICENSE" />');
        $this->license = new LicenseElement($dom->documentElement);
    }

    public function testTypeCanBeRetrieved(): void {
        $this->assertEquals('BSD-3', $this->license->getType());
    }

    public function testUrlCanBeRetrieved(): void {
        $this->assertEquals('https://some.tld/LICENSE', $this->license->getUrl());
    }
}
