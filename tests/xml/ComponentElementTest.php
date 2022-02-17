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

class ComponentElementTest extends \PHPUnit\Framework\TestCase {
    /** @var ComponentElement */
    private $component;

    protected function setUp(): void {
        $dom = new DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><component xmlns="https://phar.io/xml/manifest/1.0" name="phar-io/phive" version="0.6.0" />');
        $this->component = new ComponentElement($dom->documentElement);
    }

    public function testNameCanBeRetrieved(): void {
        $this->assertEquals('phar-io/phive', $this->component->getName());
    }

    public function testEmailCanBeRetrieved(): void {
        $this->assertEquals('0.6.0', $this->component->getVersion());
    }
}
