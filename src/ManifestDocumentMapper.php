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

class ManifestDocumentMapper {
    /**
     * @param ManifestDocument $document
     *
     * @returns Manifest
     */
    public function map(ManifestDocument $document) {
        $contains          = $document->getContainsElement();
        $type              = $this->mapType($contains);
        $copyright         = $this->mapCopyright($document->getCopyrightElement());
        $requirements      = $this->mapRequirements($document->getRequiresElement());
        $bundledComponents = $this->mapBundledComponents($document->getBundlesElement());

        return new Manifest(
            $contains->getName(),
            new Version($contains->getVersion()),
            $type,
            $copyright,
            $requirements,
            $bundledComponents
        );
    }

    /**
     * @param ContainsElement $contains
     *
     * @return Type
     *
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

                return Type::extension(
                    $extension->getFor(),
                    new VersionConstraint($extension->getCompatible())
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
     */
    private function mapCopyright(CopyrightElement $copyright) {
        $authors = new AuthorCollection();

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
     */
    private function mapRequirements(RequiresElement $requires) {
        $collection = new RequirementCollection();
        $phpElement = $requires->getPHPElement();

        $collection->add(
            new PhpVersionRequirement(
                new VersionConstraint($phpElement->getVersion())
            )
        );

        foreach ($phpElement->getExtElements() as $extElement) {
            $collection->add(
                new PhpExtensionRequirement($extElement->getName())
            );
        }

        return $collection;
    }

    /**
     * @param BundlesElement $bundles
     *
     * @return BundledComponentCollection
     */
    private function mapBundledComponents(BundlesElement $bundles) {
        $collection = new BundledComponentCollection();
        foreach ($bundles->getComponentElements() as $componentElement) {
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
