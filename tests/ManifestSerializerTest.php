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
use function file_get_contents;
use function sys_get_temp_dir;
use function uniqid;
use function unlink;

/**
 * @covers \PharIo\Manifest\ManifestSerializer
 *
 * @uses \PharIo\Manifest\ApplicationName
 * @uses \PharIo\Manifest\Author
 * @uses \PharIo\Manifest\AuthorCollection
 * @uses \PharIo\Manifest\AuthorCollectionIterator
 * @uses \PharIo\Manifest\AuthorElement
 * @uses \PharIo\Manifest\AuthorElementCollection
 * @uses \PharIo\Manifest\BundledComponent
 * @uses \PharIo\Manifest\BundledComponentCollection
 * @uses \PharIo\Manifest\BundledComponentCollectionIterator
 * @uses \PharIo\Manifest\BundlesElement
 * @uses \PharIo\Manifest\ComponentElement
 * @uses \PharIo\Manifest\ComponentElementCollection
 * @uses \PharIo\Manifest\ContainsElement
 * @uses \PharIo\Manifest\CopyrightElement
 * @uses \PharIo\Manifest\CopyrightInformation
 * @uses \PharIo\Manifest\ElementCollection
 * @uses \PharIo\Manifest\Email
 * @uses \PharIo\Manifest\ExtElement
 * @uses \PharIo\Manifest\ExtElementCollection
 * @uses \PharIo\Manifest\License
 * @uses \PharIo\Manifest\LicenseElement
 * @uses \PharIo\Manifest\Manifest
 * @uses \PharIo\Manifest\ManifestDocument
 * @uses \PharIo\Manifest\ManifestDocumentMapper
 * @uses \PharIo\Manifest\ManifestElement
 * @uses \PharIo\Manifest\ManifestLoader
 * @uses \PharIo\Manifest\PhpElement
 * @uses \PharIo\Manifest\PhpExtensionRequirement
 * @uses \PharIo\Manifest\PhpVersionRequirement
 * @uses \PharIo\Manifest\RequirementCollection
 * @uses \PharIo\Manifest\RequirementCollectionIterator
 * @uses \PharIo\Manifest\RequiresElement
 * @uses \PharIo\Manifest\Type
 * @uses \PharIo\Manifest\Url
 * @uses \PharIo\Version\Version
 * @uses \PharIo\Version\VersionConstraint
 */
class ManifestSerializerTest extends \PHPUnit\Framework\TestCase {
    /**
     * @dataProvider dataProvider
     *
     * @uses \PharIo\Manifest\Application
     * @uses \PharIo\Manifest\Library
     * @uses \PharIo\Manifest\Extension
     * @uses \PharIo\Manifest\ExtensionElement
     */
    public function testCanSerializeToString($expected): void {
        $manifest = ManifestLoader::fromString($expected);

        $serializer = new ManifestSerializer();

        $this->assertXmlStringEqualsXmlString(
            $expected,
            $serializer->serializeToString($manifest)
        );
    }

    public function dataProvider() {
        return [
            'application' => [file_get_contents(__DIR__ . '/_fixture/phpunit-5.6.5.xml')],
            'library'     => [file_get_contents(__DIR__ . '/_fixture/library.xml')],
            'extension'   => [file_get_contents(__DIR__ . '/_fixture/extension.xml')]
        ];
    }

    /**
     * @uses \PharIo\Manifest\Library
     * @uses \PharIo\Manifest\ApplicationName
     */
    public function testCanSerializeToFile(): void {
        $src        = __DIR__ . '/_fixture/library.xml';
        $dest       = sys_get_temp_dir() . '/' . uniqid('serializer', true);
        $manifest   = ManifestLoader::fromFile($src);
        $serializer = new ManifestSerializer();
        $serializer->serializeToFile($manifest, $dest);
        $this->assertXmlFileEqualsXmlFile($src, $dest);
        unlink($dest);
    }

    /**
     * @uses \PharIo\Manifest\ApplicationName
     */
    public function testCanHandleUnknownType(): void {
        $type     = $this->getMockForAbstractClass(Type::class);
        $manifest = new Manifest(
            new ApplicationName('testvendor/testname'),
            new Version('1.0.0'),
            $type,
            new CopyrightInformation(
                new AuthorCollection(),
                new License('bsd-3', new Url('https://some/uri'))
            ),
            new RequirementCollection(),
            new BundledComponentCollection()
        );

        $serializer = new ManifestSerializer();
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/_fixture/custom.xml',
            $serializer->serializeToString($manifest)
        );
    }
}
