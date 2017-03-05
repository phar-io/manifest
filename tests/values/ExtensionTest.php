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

use PharIo\Version\AnyVersionConstraint;
use PHPUnit\Framework\TestCase;

/**
 * @covers PharIo\Manifest\Extension
 * @covers PharIo\Manifest\Type
 *
 * @uses \PharIo\Version\VersionConstraint
 */
class ExtensionTest extends TestCase {
    /**
     * @var Extension
     */
    private $type;

    /**
     * @var ApplicationName|\PHPUnit_Framework_MockObject_MockObject
     */
    private $name;

    protected function setUp() {
        $this->name = $this->createMock(ApplicationName::class);
        $this->type = Type::extension($this->name, new AnyVersionConstraint);
    }

    public function testCanBeCreated() {
        $this->assertInstanceOf(Extension::class, $this->type);
    }

    public function testIsNotApplication() {
        $this->assertFalse($this->type->isApplication());
    }

    public function testIsNotLibrary() {
        $this->assertFalse($this->type->isLibrary());
    }

    public function testIsExtension() {
        $this->assertTrue($this->type->isExtension());
    }

    public function testApplicationCanBeRetrieved()
    {
        $this->assertInstanceOf(ApplicationName::class, $this->type->getApplicationName());
    }

    public function testApplicationCanBeQueried()
    {
        $this->name->method('isEqual')->willReturn(true);
        $this->assertTrue(
            $this->type->isExtensionFor($this->createMock(ApplicationName::class))
        );
    }
}
