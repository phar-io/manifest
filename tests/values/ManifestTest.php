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

use PharIo\Version\Version;
use PharIo\Version\AnyVersionConstraint;

use PHPUnit\Framework\TestCase;

/**
 * @covers PharIo\Manifest\Manifest
 *
 * @uses PharIo\Manifest\Author
 * @uses PharIo\Manifest\AuthorCollection
 * @uses PharIo\Manifest\BundledComponent
 * @uses PharIo\Manifest\BundledComponentCollection
 * @uses PharIo\Manifest\CopyrightInformation
 * @uses PharIo\Manifest\Email
 * @uses PharIo\Manifest\License
 * @uses PharIo\Manifest\RequirementCollection
 * @uses PharIo\Manifest\PhpVersionRequirement
 * @uses PharIo\Manifest\Type
 * @uses PharIo\Manifest\Application
 * @uses PharIo\Manifest\Url
 * @uses PharIo\Manifest\Version
 * @uses PharIo\Manifest\VersionConstraint
 */
class ManifestTest extends TestCase {
    /**
     * @var string
     */
    private $name = 'phpunit/phpunit';

    /**
     * @var Version
     */
    private $version;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var CopyrightInformation
     */
    private $copyrightInformation;

    /**
     * @var RequirementCollection
     */
    private $requirements;

    /**
     * @var BundledComponentCollection
     */
    private $bundledComponents;

    /**
     * @var Manifest
     */
    private $manifest;

    protected function setUp() {
        $this->version = new Version('5.6.5');

        $this->type = Type::application();

        $author  = new Author('Joe Developer', new Email('user@example.com'));
        $license = new License('BSD-3-Clause', new Url('https://github.com/sebastianbergmann/phpunit/blob/master/LICENSE'));

        $authors = new AuthorCollection;
        $authors->add($author);

        $this->copyrightInformation = new CopyrightInformation($authors, $license);

        $this->requirements = new RequirementCollection;
        $this->requirements->add(new PhpVersionRequirement(new AnyVersionConstraint));

        $this->bundledComponents = new BundledComponentCollection;
        $this->bundledComponents->add(new BundledComponent('phpunit/php-code-coverage', new Version('4.0.2')));

        $this->manifest = new Manifest(
            $this->name,
            $this->version,
            $this->type,
            $this->copyrightInformation,
            $this->requirements,
            $this->bundledComponents
        );
    }

    public function testCanBeCreated() {
        $this->assertInstanceOf(Manifest::class, $this->manifest);
    }

    public function testNameCanBeRetrieved() {
        $this->assertEquals($this->name, $this->manifest->getName());
    }

    public function testVersionCanBeRetrieved() {
        $this->assertEquals($this->version, $this->manifest->getVersion());
    }

    public function testTypeCanBeRetrieved() {
        $this->assertEquals($this->type, $this->manifest->getType());
    }

    public function testTypeCanBeQueried() {
        $this->assertTrue($this->manifest->isApplication());
        $this->assertFalse($this->manifest->isLibrary());
        $this->assertFalse($this->manifest->isExtension());
    }

    public function testCopyrightInformationCanBeRetrieved() {
        $this->assertEquals($this->copyrightInformation, $this->manifest->getCopyrightInformation());
    }

    public function testRequirementsCanBeRetrieved() {
        $this->assertEquals($this->requirements, $this->manifest->getRequirements());
    }

    public function testBundledComponentsCanBeRetrieved() {
        $this->assertEquals($this->bundledComponents, $this->manifest->getBundledComponents());
    }

    /**
     * @uses PharIo\Manifest\Extension
     */
    public function testExtendedApplicationCanBeQueriedForExtension()
    {
        $manifest = new Manifest(
            'foo',
            new Version('1.0.0'),
            Type::extension('bar', new AnyVersionConstraint),
            $this->copyrightInformation,
            new RequirementCollection,
            new BundledComponentCollection
        );

        $this->assertTrue($manifest->isExtensionFor('bar'));
    }
}
