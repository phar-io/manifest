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
 * @covers PharIo\Manifest\PhpVersionRequirement
 */
class PhpVersionRequirementTest extends TestCase
{
    public function testCanBeCreated()
    {
        $this->assertInstanceOf(PhpVersionRequirement::class, new PhpVersionRequirement('7.1.0'));
    }

    public function testCanBeUsedAsString()
    {
        $this->assertEquals('7.1.0', new PhpVersionRequirement('7.1.0'));
    }
}
