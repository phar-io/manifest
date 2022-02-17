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
 * @covers PharIo\Manifest\CopyrightInformation
 *
 * @uses PharIo\Manifest\AuthorCollection
 * @uses PharIo\Manifest\AuthorCollectionIterator
 * @uses PharIo\Manifest\Author
 * @uses PharIo\Manifest\Email
 * @uses PharIo\Manifest\License
 * @uses PharIo\Manifest\Url
 */
class CopyrightInformationTest extends TestCase {
    /** @var CopyrightInformation */
    private $copyrightInformation;

    /** @var Author */
    private $author;

    /** @var License */
    private $license;

    protected function setUp(): void {
        $this->author  = new Author('Joe Developer', new Email('user@example.com'));
        $this->license = new License('BSD-3-Clause', new Url('https://github.com/sebastianbergmann/phpunit/blob/master/LICENSE'));

        $authors = new AuthorCollection;
        $authors->add($this->author);

        $this->copyrightInformation = new CopyrightInformation($authors, $this->license);
    }

    public function testCanBeCreated(): void {
        $this->assertInstanceOf(CopyrightInformation::class, $this->copyrightInformation);
    }

    public function testAuthorsCanBeRetrieved(): void {
        $this->assertContains($this->author, $this->copyrightInformation->getAuthors());
    }

    public function testLicenseCanBeRetrieved(): void {
        $this->assertEquals($this->license, $this->copyrightInformation->getLicense());
    }
}
