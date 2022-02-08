<?php declare(strict_types=1);
/*
 * This file is part of PharIo\Manifest.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PharIo\Manifest;

use PharIo\Version\InvalidVersionException;
use PharIo\Version\Version;

class ManifestJsonDocument
{
    public static function fromFile(string $filename): ManifestDocument {
        if (!\file_exists($filename)) {
            throw new ManifestDocumentException(
                \sprintf('File "%s" not found', $filename)
            );
        }

        return self::fromString(
            \file_get_contents($filename)
        );
    }

    public static function fromString(string $jsonString): ManifestDocument {
        $decodedData = self::decode($jsonString);
        $composerDoc = new ComposerDocument(\sha1($jsonString), $decodedData);

        $doc = new \DOMDocument('1.0', 'utf-8');
        $doc->formatOutput = true;

        $root = $doc->createElementNS(ManifestDocument::XMLNS, 'phar');
        $root = $doc->appendChild($root);

        // contains name="vendor/package" version="0.0.0-dev" type="library"
        $containsTag = $doc->createElement('contains');
        // @see https://getcomposer.org/doc/04-schema.md#name
        $nameAttr = $doc->createAttribute('name');
        $nameAttr->value = $composerDoc->getName();
        $containsTag->appendChild($nameAttr);
        // @see https://getcomposer.org/doc/04-schema.md#version
        $versionAttr = $doc->createAttribute('version');
        $versionAttr->value = $composerDoc->getVersion();
        $containsTag->appendChild($versionAttr);
        // @see https://getcomposer.org/doc/04-schema.md#type
        $typeAttr = $doc->createAttribute('type');
        $typeAttr->value = $composerDoc->getType();
        $containsTag->appendChild($typeAttr);
        $root->appendChild($containsTag);

        // copyright (authors list)
        $copyrightTag = $doc->createElement('copyright');
        // @see https://getcomposer.org/doc/04-schema.md#authors
        foreach ($composerDoc->getAuthors()->getAuthors() as $author) {
            // only author name is required,
            // @see https://github.com/composer/composer/blob/2.2/res/composer-schema.json#L609-L637
            $authorTag = $doc->createElement('author');
            $nameAttr = $doc->createAttribute('name');
            $nameAttr->value = $author->getName();
            $authorTag->appendChild($nameAttr);
            $emailAttr = $doc->createAttribute('email');
            $emailAttr->value = $author->getEmail()->asString();
            $authorTag->appendChild($emailAttr);
            $copyrightTag->appendChild($authorTag);
        }
        // copyright (license)
        // @see https://getcomposer.org/doc/04-schema.md#license
        $licenseTag = $doc->createElement('license');
        $typeAttr = $doc->createAttribute('type');
        $typeAttr->value = $composerDoc->getLicense();
        $licenseTag->appendChild($typeAttr);
        // @see https://spdx.org/licenses/
        $urlAttr = $doc->createAttribute('url');
        $urlAttr->value = \sprintf('https://spdx.org/licenses/%s.html', $composerDoc->getLicense());
        $licenseTag->appendChild($urlAttr);
        $copyrightTag->appendChild($licenseTag);
        $root->appendChild($copyrightTag);

        // requires
        // @see https://getcomposer.org/doc/04-schema.md#require
        $requiresTag = $doc->createElement('requires');
        $phpTag = $doc->createElement('php');
        $versionAttr = $doc->createAttribute('version');
        $versionAttr->value = $composerDoc->getPlatform()->getPhp();
        $phpTag->appendChild($versionAttr);
        foreach ($composerDoc->getPlatform()->getExtensions() as $extension) {
            $extTag = $doc->createElement('ext');
            $nameAttr = $doc->createAttribute('name');
            $nameAttr->value = $extension;
            $extTag->appendChild($nameAttr);
            $phpTag->appendChild($extTag);
        }
        $requiresTag->appendChild($phpTag);
        $root->appendChild($requiresTag);

        // bundles
        $bundlesTag = self::buildBundles($composerDoc->getPackages(), $doc);
        if ($bundlesTag->hasChildNodes()) {
            $root->appendChild($bundlesTag);
        }

        $xmlString = $doc->saveXML();
        return ManifestDocument::fromString($xmlString);
    }

    /**
     * @param Package[] $packages
     */
    private static function buildBundles(array $packages, \DOMDocument $doc): \DOMElement
    {
        $bundlesTag = $doc->createElement('bundles');

        foreach ($packages as $package) {
            $componentTag = $doc->createElement('component');
            $nameAttr = $doc->createAttribute('name');
            $nameAttr->value = $package->getName();
            $componentTag->appendChild($nameAttr);
            $versionAttr = $doc->createAttribute('version');
            try {
                new Version($package->getVersion());
                $versionAttr->value = $package->getVersion();
            } catch (InvalidVersionException $e) {
                $versionAttr->value = '0.0.0-dev';
            }
            $componentTag->appendChild($versionAttr);
            $bundlesTag->appendChild($componentTag);
        }

        return $bundlesTag;
    }

    /**
     * Decodes JSON data
     *
     * @return array<string, mixed>
     * Credits to Symfony\Component\Serializer\Encoder\JsonDecode for chunk of code
     */
    private static function decode(string $data): array
    {
        $flags = (\PHP_VERSION_ID >= 70300) ? \JSON_THROW_ON_ERROR : 0;

        try {
            $decodedData = \json_decode($data, true, 512, $flags);
        } catch (\JsonException $e) {
            throw new NotEncodableValueException($e->getMessage(), 0, $e);
        }

        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new NotEncodableValueException(\json_last_error_msg());
        }

        return $decodedData;
    }
}
