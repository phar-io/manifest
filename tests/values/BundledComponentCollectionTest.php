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

use PharIo\Version\Version;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PharIo\Manifest\BundledComponentCollection
 * @covers \PharIo\Manifest\BundledComponentCollectionIterator
 *
 * @uses \PharIo\Manifest\BundledComponent
 * @uses \PharIo\Version\Version
 */
class BundledComponentCollectionTest extends TestCase {
    /** @var BundledComponentCollection */
    private $collection;

    /** @var BundledComponent */
    private $item;

    protected function setUp(): void {
        $this->collection = new BundledComponentCollection;
        $this->item       = new BundledComponent('phpunit/php-code-coverage', new Version('4.0.2'));
    }

    public function testCanBeCreated(): void {
        $this->assertInstanceOf(BundledComponentCollection::class, $this->collection);
    }

    public function testCanBeCounted(): void {
        $this->collection->add($this->item);

        $this->assertCount(1, $this->collection);
    }

    public function testCanBeIterated(): void {
        $this->collection->add($this->createMock(BundledComponent::class));
        $this->collection->add($this->item);

        $this->assertContains($this->item, $this->collection);
    }

    public function testKeyPositionCanBeRetrieved(): void {
        $this->collection->add($this->item);

        foreach ($this->collection as $key => $item) {
            $this->assertEquals(0, $key);
        }
    }
}
