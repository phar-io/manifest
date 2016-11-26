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
 * @covers PharIo\Manifest\VersionConstraint
 */
class VersionConstraintTest extends TestCase {
    public function testCanBeCreatedForValidVersionConstraint() {
        $this->assertInstanceOf(VersionConstraint::class, new VersionConstraint('^5.6 || ^7.0'));
    }

    public function testCanBeUsedAsString() {
        $this->assertEquals('^5.6 || ^7.0', new VersionConstraint('^5.6 || ^7.0'));
    }

    /**
     * @covers PharIo\Manifest\InvalidVersionConstraintException
     */
    public function testCannotBeCreatedForInvalidVersionConstraint() {
        $this->markTestSkipped();
    }
}
