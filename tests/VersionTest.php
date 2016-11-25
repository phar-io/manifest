<?php
/*
 * This file is part of PharIo\Manifest.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PharIo\Manifest;

use PHPUnit\Framework\TestCase;

/**
 * @covers PharIo\Manifest\Version
 */
class VersionTest extends TestCase
{
    public function testCanBeCreatedForValidVersion()
    {
        $this->assertInstanceOf(Version::class, new Version('5.6.5'));
    }

    public function testCanBeUsedAsString()
    {
        $this->assertEquals('5.6.5', new Version('5.6.5'));
    }

    /**
     * @covers PharIo\Manifest\InvalidVersionException
     */
    public function testCannotBeCreatedForInvalidVersion()
    {
        $this->expectException(InvalidVersionException::class);

        new Version('invalid');
    }
}
