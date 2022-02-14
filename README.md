# Manifest

Component for reading [phar.io](https://phar.io/) manifest information from a [PHP Archive (PHAR)](http://php.net/phar).

## Installation

You can add this library as a local, per-project dependency to your project using [Composer](https://getcomposer.org/):

    composer require phar-io/manifest

If you only need this library during development, for instance to run your project's test suite, then you should add it as a development-time dependency:

    composer require --dev phar-io/manifest

## Usage Examples

### Read from `manifest.xml`
```php
use PharIo\Manifest\ManifestLoader;
use PharIo\Manifest\ManifestSerializer;

$manifest = ManifestLoader::fromFile('manifest.xml');

var_dump($manifest);

echo (new ManifestSerializer)->serializeToString($manifest);
```

### Create via API
```php
$bundled = new \PharIo\Manifest\BundledComponentCollection();
$bundled->add(
    new \PharIo\Manifest\BundledComponent('vendor/packageA', new \PharIo\Version\Version('1.2.3-dev')
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
    $bundled
);

echo (new ManifestSerializer)->serializeToString($manifest);
```

