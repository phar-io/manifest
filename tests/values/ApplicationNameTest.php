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

class ApplicationNameTest extends TestCase {
    public function testCanBeCreatedWithValidName(): void {
        $this->assertInstanceOf(
            ApplicationName::class,
            new ApplicationName('foo/bar')
        );
    }

    public function testUsingInvalidFormatForNameThrowsException(): void {
        $this->expectException(InvalidApplicationNameException::class);
        $this->expectExceptionCode(InvalidApplicationNameException::InvalidFormat);
        new ApplicationName('foo');
    }

    public function testReturnsTrueForEqualNamesWhenCompared(): void {
        $app = new ApplicationName('foo/bar');
        $this->assertTrue(
            $app->isEqual($app)
        );
    }

    public function testReturnsFalseForNonEqualNamesWhenCompared(): void {
        $app1 = new ApplicationName('foo/bar');
        $app2 = new ApplicationName('foo/foo');
        $this->assertFalse(
            $app1->isEqual($app2)
        );
    }

    public function testCanBeConvertedToString(): void {
        $this->assertEquals(
            'foo/bar',
            (new ApplicationName('foo/bar'))->asString()
        );
    }
}
