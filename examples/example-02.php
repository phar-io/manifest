<?php declare(strict_types = 1);
/**
 * Thanks to @llaville for this example
 */
use PharIo\Manifest\ManifestSerializer;

require __DIR__ . '/../vendor/autoload.php';

$bundledComponentCollection = new \PharIo\Manifest\BundledComponentCollection();
$bundledComponentCollection->add(
    new \PharIo\Manifest\BundledComponent(
        'vendor/packageA',
        new \PharIo\Version\Version('0.0.0-dev')
    )
);

$manifest = new PharIo\Manifest\Manifest(
    new \PharIo\Manifest\ApplicationName('vendor/package'),
    new \PharIo\Version\Version('1.0.0'),
    new \PharIo\Manifest\Library(),
    new \PharIo\Manifest\CopyrightInformation(
        new \PharIo\Manifest\AuthorCollection(),
        new \PharIo\Manifest\License(
            'BSD-3-Clause',
            new \PharIo\Manifest\Url('https://spdx.org/licenses/BSD-3-Clause.html')
        )
    ),
    new \PharIo\Manifest\RequirementCollection(),
    $bundledComponentCollection
);

echo (new ManifestSerializer)->serializeToString($manifest);

/*
 * Output produced
 *
<?xml version="1.0" encoding="UTF-8"?>
<phar xmlns="https://phar.io/xml/manifest/1.0">
    <contains name="vendor/package" version="1.0.0" type="library"/>
    <copyright>
        <license type="BSD-3-Clause" url="https://spdx.org/licenses/BSD-3-Clause.html"/>
    </copyright>
    <requires>
        <php version="*"/>
    </requires>
    <bundles>
        <component name="vendor/packageA" version="0.0.0-dev"/>
    </bundles>
</phar>
 */
