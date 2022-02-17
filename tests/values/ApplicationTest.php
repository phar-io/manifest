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
 * @covers PharIo\Manifest\Application
 * @covers PharIo\Manifest\Type
 */
class ApplicationTest extends TestCase {
    /** @var Application */
    private $type;

    protected function setUp(): void {
        $this->type = Type::application();
    }

    public function testCanBeCreated(): void {
        $this->assertInstanceOf(Application::class, $this->type);
    }

    public function testIsApplication(): void {
        $this->assertTrue($this->type->isApplication());
    }

    public function testIsNotLibrary(): void {
        $this->assertFalse($this->type->isLibrary());
    }

    public function testIsNotExtension(): void {
        $this->assertFalse($this->type->isExtension());
    }
}
