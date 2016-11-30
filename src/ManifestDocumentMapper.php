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

use PharIo\Version\UnsupportedVersionConstraintException;
use PharIo\Version\Version;
use PharIo\Version\VersionConstraint;
use PharIo\Version\VersionConstraintParser;

class ManifestDocumentMapper {
    /**
     * @param ManifestDocument $document
     *
     * @returns Manifest
     *
     * @throws ManifestDocumentMapperException
     */
    public function map(ManifestDocument $document) {
        try {
            $contains          = $document->getContainsElement();
            $type              = $this->mapType($contains);
            $copyright         = $this->mapCopyright($document->getCopyrightElement());
            $requirements      = $this->mapRequirements($document->getRequiresElement());
            $bundledComponents = $this->mapBundledComponents($document);

            return new Manifest(
                $contains->getName(),
                new Version($contains->getVersion()),
                $type,
                $copyright,
                $requirements,
                $bundledComponents
            );
        } catch(Exception $e) {
            throw new ManifestDocumentMapperException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param ContainsElement $contains
     *
     * @return Type
     *
     * @throws UnsupportedVersionConstraintException
     * @throws ManifestDocumentMapperException
     */
    private function mapType(ContainsElement $contains) {
        switch ($contains->getType()) {
            case 'application':
                return Type::application();
            case 'library':
                return Type::library();
            case 'extension':
                /** @var ExtensionElement $extension */
                $extension = $contains->getExtensionElement();

                $parser = new VersionConstraintParser;

                return Type::extension(
                    $extension->getFor(),
                    $parser->parse($extension->getCompatible())
                );
        }

        throw new ManifestDocumentMapperException(
            sprintf('Unsupported type %s', $contains->getType())
        );
    }

    /**
     * @param CopyrightElement $copyright
     *
     * @return CopyrightInformation
     *
     * @throws InvalidUrlException
     * @throws InvalidEmailException
     */
    private function mapCopyright(CopyrightElement $copyright) {
        $authors = new AuthorCollection();

        foreach($copyright->getAuthorElements() as $authorElement) {
            $authors->add(
                new Author(
                    $authorElement->getName(),
                    new Email($authorElement->getEmail())
                )
            );
        }

        $licenseElement = $copyright->getLicenseElement();
        $license        = new License(
            $licenseElement->getType(),
            new Url($licenseElement->getUrl())
        );

        return new CopyrightInformation(
            $authors,
            $license
        );
    }

    /**
     * @param RequiresElement $requires
     *
     * @return RequirementCollection
     *
     * @throws UnsupportedVersionConstraintException
     */
    private function mapRequirements(RequiresElement $requires) {
        $collection = new RequirementCollection();
        $phpElement = $requires->getPHPElement();
        $parser = new VersionConstraintParser;

        $collection->add(
            new PhpVersionRequirement(
                $parser->parse($phpElement->getVersion())
            )
        );

        if (!$phpElement->hasExtElements()) {
            return $collection;
        }

        foreach($phpElement->getExtElements() as $extElement) {
            $collection->add(
                new PhpExtensionRequirement($extElement->getName())
            );
        }

        return $collection;
    }

    /**
     * @param ManifestDocument $document
     *
     * @return BundledComponentCollection
     */
    private function mapBundledComponents(ManifestDocument $document) {
        $collection = new BundledComponentCollection();

        if (!$document->hasBundlesElement()) {
            return $collection;
        }

        foreach($document->getBundlesElement()->getComponentElements() as $componentElement) {
            $collection->add(
                new BundledComponent(
                    $componentElement->getName(),
                    new Version(
                        $componentElement->getVersion()
                    )
                )
            );
        }

        return $collection;
    }
}
