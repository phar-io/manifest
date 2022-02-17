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

use PHPUnit\Framework\TestCase;

/**
 * @covers PharIo\Manifest\Url
 */
class UrlTest extends TestCase {
    public function testCanBeCreatedForValidUrl(): void {
        $this->assertInstanceOf(Url::class, new Url('https://phar.io/'));
    }

    public function testCanBeConvertedToString(): void {
        $this->assertEquals('https://phar.io/', (new Url('https://phar.io/'))->asString());
    }

    /**
     * @covers PharIo\Manifest\InvalidUrlException
     */
    public function testCannotBeCreatedForInvalidUrl(): void {
        $this->expectException(InvalidUrlException::class);

        new Url('invalid');
    }
}
