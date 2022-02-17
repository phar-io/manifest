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
 * @covers PharIo\Manifest\Library
 * @covers PharIo\Manifest\Type
 */
class LibraryTest extends TestCase {
    /** @var Library */
    private $type;

    protected function setUp(): void {
        $this->type = Type::library();
    }

    public function testCanBeCreated(): void {
        $this->assertInstanceOf(Library::class, $this->type);
    }

    public function testIsNotApplication(): void {
        $this->assertFalse($this->type->isApplication());
    }

    public function testIsLibrary(): void {
        $this->assertTrue($this->type->isLibrary());
    }

    public function testIsNotExtension(): void {
        $this->assertFalse($this->type->isExtension());
    }
}
