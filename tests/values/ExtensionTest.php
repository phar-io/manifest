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

use PharIo\Version\AnyVersionConstraint;
use PharIo\Version\Version;
use PharIo\Version\VersionConstraint;
use PharIo\Version\VersionConstraintParser;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @covers \PharIo\Manifest\Extension
 * @covers \PharIo\Manifest\Type
 *
 * @uses \PharIo\Version\VersionConstraint
 * @uses \PharIo\Manifest\ApplicationName
 */
class ExtensionTest extends TestCase {
    /** @var Extension */
    private $type;

    /** @var ApplicationName|PHPUnit_Framework_MockObject_MockObject */
    private $name;

    protected function setUp(): void {
        $this->name = $this->createMock(ApplicationName::class);
        $this->type = Type::extension($this->name, new AnyVersionConstraint);
    }

    public function testCanBeCreated(): void {
        $this->assertInstanceOf(Extension::class, $this->type);
    }

    public function testIsNotApplication(): void {
        $this->assertFalse($this->type->isApplication());
    }

    public function testIsNotLibrary(): void {
        $this->assertFalse($this->type->isLibrary());
    }

    public function testIsExtension(): void {
        $this->assertTrue($this->type->isExtension());
    }

    public function testApplicationCanBeRetrieved(): void {
        $this->assertInstanceOf(ApplicationName::class, $this->type->getApplicationName());
    }

    public function testVersionConstraintCanBeRetrieved(): void {
        $this->assertInstanceOf(
            VersionConstraint::class,
            $this->type->getVersionConstraint()
        );
    }

    public function testApplicationCanBeQueried(): void {
        $this->name->method('isEqual')->willReturn(true);
        $this->assertTrue(
            $this->type->isExtensionFor($this->createMock(ApplicationName::class))
        );
    }

    public function testCompatibleWithReturnsTrueForMatchingVersionConstraintAndApplication(): void {
        $app       = new ApplicationName('foo/bar');
        $extension = Type::extension($app, (new VersionConstraintParser)->parse('^1.0'));
        $version   = new Version('1.0.0');

        $this->assertTrue(
            $extension->isCompatibleWith($app, $version)
        );
    }

    public function testCompatibleWithReturnsFalseForNotMatchingVersionConstraint(): void {
        $app       = new ApplicationName('foo/bar');
        $extension = Type::extension($app, (new VersionConstraintParser)->parse('^1.0'));
        $version   = new Version('2.0.0');

        $this->assertFalse(
            $extension->isCompatibleWith($app, $version)
        );
    }

    public function testCompatibleWithReturnsFalseForNotMatchingApplication(): void {
        $app1      = new ApplicationName('foo/bar');
        $app2      = new ApplicationName('foo/foo');
        $extension = Type::extension($app1, (new VersionConstraintParser)->parse('^1.0'));
        $version   = new Version('1.0.0');

        $this->assertFalse(
            $extension->isCompatibleWith($app2, $version)
        );
    }
}
