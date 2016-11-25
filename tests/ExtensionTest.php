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
 * @covers PharIo\Manifest\Extension
 * @covers PharIo\Manifest\Type
 */
class ExtensionTest extends TestCase
{
    /**
     * @var Extension
     */
    private $type;

    protected function setUp()
    {
        $this->type = Type::extension();
    }

    public function testCanBeCreated()
    {
        $this->assertInstanceOf(Extension::class, $this->type);
    }

    public function testIsNotApplication()
    {
        $this->assertFalse($this->type->isApplication());
    }

    public function testIsNotLibrary()
    {
        $this->assertFalse($this->type->isLibrary());
    }

    public function testIsExtension()
    {
        $this->assertTrue($this->type->isExtension());
    }
}