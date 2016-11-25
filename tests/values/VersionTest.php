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
 * @covers \PharIo\Manifest\Version
 */
class VersionTest extends TestCase {
    /**
     * @dataProvider validVersionStrings
     */
    public function testCanBeCreatedForValidVersion($versionString) {
        $this->assertInstanceOf(Version::class, new Version($versionString));
    }

    public function validVersionStrings() {
        return [
            '1.0.0'        => ['1.0.0'],
            '15.0.23'      => ['15.0.23'],
            '1.0.0-dev'    => ['1.0.0-dev'],
            '1.0.0-alpha1' => ['1.0.0-alpha1'],
            '1.0.0-beta2'  => ['1.0.0-beta2'],
            '1.0.0-a3'     => ['1.0.0-a3'],
            '1.0.0-b4'     => ['1.0.0-b4'],
            '1.0.0-RC5'    => ['1.0.0-RC5'],
            '2.0.4-p6'     => ['2.0.4-p6'],
            '2.0.4-patch7' => ['2.0.4-patch7']
        ];
    }

    /**
     * @covers       \PharIo\Manifest\InvalidVersionException
     * @dataProvider invalidVersionStrings
     */
    public function testCannotBeCreatedForInvalidVersion($versionString) {
        $this->expectException(InvalidVersionException::class);
        new Version($versionString);
    }

    public function invalidVersionStrings() {
        return [
            'a.b.c'         => ['a.b.c'],
            'not-a-version' => ['not-a-version'],
            '1.0'           => ['1.0'],
            '1.2.3-foo'     => ['1.2.3-foo'],
            '1.2.3-foo2'    => ['1.2.3-foo2'],
            '1.2.3.4'       => ['1.2.3.4'],
            '1.2.3_foo'     => ['1.2.3_foo'],
            '1.0-beta1'     => ['1.0-beta1'],
            '01.2.3'        => ['01.2.3'],
            '0.00.1'        => ['0.00.1'],
            '0-a1'          => ['0-a1']
        ];
    }

    public function testCanBeUsedAsString() {
        $this->assertEquals('5.6.5', new Version('5.6.5'));
    }

    public function testMajorVersionCanBeRetrieved() {
        $this->assertEquals(1, (new Version('1.0.0'))->getMajorVersion());
    }

    public function testMinorVersionCanBeRetrieved() {
        $this->assertEquals(1, (new Version('0.1.0'))->getMinorVersion());
    }

    public function testPatchLevelCanBeRetrieved() {
        $this->assertEquals(1, (new Version('0.0.1'))->getPatchLevel());
    }

    public function testIsStableVersionReturnsTrueOnStableRelease() {
        $this->assertTrue((new Version('1.0.0'))->isStableVersion());
    }

    public function testIsStableVersionReturnsFalseOnNonStableRelease() {
        $this->assertFalse((new Version('1.0.0-dev'))->isStableVersion());
    }

    public function testIsDevelopmentVersionReturnsTrueOnDevelopmentRelease() {
        $this->assertTrue((new Version('1.0.0-dev'))->isDevelopmentVersion());
    }

    public function testIsDevelopmentVersionReturnsFalseOnNonDevelopmentRelease() {
        $this->assertFalse((new Version('1.0.0'))->isDevelopmentVersion());
    }

    public function testIsPatchVersionReturnsTrueOnPatchRelease() {
        $this->assertTrue((new Version('1.0.0-p1'))->isPatchVersion());
    }

    public function testIsPatchVersionReturnsFalseOnNonPatchRelease() {
        $this->assertFalse((new Version('1.0.0'))->isPatchVersion());
    }

    public function testIsAlphaVersionReturnsTrueOnAlphaRelease() {
        $this->assertTrue((new Version('1.0.0-a1'))->isAlphaVersion());
    }

    public function testIsAlphaVersionReturnsFalseOnNonAlphaRelease() {
        $this->assertFalse((new Version('1.0.0'))->isAlphaVersion());
    }

    public function testIsBetaVersionReturnsTrueOnBetaRelease() {
        $this->assertTrue((new Version('1.0.0-b1'))->isBetaVersion());
    }

    public function testIsBetaVersionReturnsFalseOnNonBetaRelease() {
        $this->assertFalse((new Version('1.0.0'))->isBetaVersion());
    }

    public function testCorrectReleaseTypeIsReturnedForBetaVersion() {
        $this->assertEquals('beta', (new Version('1.0.0-b1'))->getReleaseType());
    }

    public function testCorrectReleaseTypeCountIsReturnedForBetaVersion() {
        $this->assertEquals(1, (new Version('1.0.0-beta'))->getReleaseTypeCount());
    }

    public function testCorrectReleaseTypeCountIsReturnedForSecondBetaVersion() {
        $this->assertEquals(2, (new Version('1.0.0-b2'))->getReleaseTypeCount());
    }

}
