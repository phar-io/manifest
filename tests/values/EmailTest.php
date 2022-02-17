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
 * @covers \PharIo\Manifest\Email
 */
class EmailTest extends TestCase {
    public function testCanBeCreatedForValidEmail(): void {
        $this->assertInstanceOf(Email::class, new Email('user@example.com'));
    }

    public function testCanBeRequestedAsString(): void {
        $this->assertEquals('user@example.com', (new Email('user@example.com'))->asString());
    }

    /**
     * @covers \PharIo\Manifest\InvalidEmailException
     */
    public function testCannotBeCreatedForInvalidEmail(): void {
        $this->expectException(InvalidEmailException::class);

        new Email('invalid');
    }
}
