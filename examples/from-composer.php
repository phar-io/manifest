<?php
/*
 * This file is part of PharIo\Manifest.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ . '/../vendor/autoload.php';

use PharIo\Manifest\ManifestSerializer2;

//echo ManifestSerializer2::fromComposer(dirname(__DIR__) . '/composer.json');
/*
 * Output produced
 *
<?xml version="1.0" encoding="utf-8"?>
<phar xmlns="https://phar.io/xml/manifest/1.0">
  <requires>
    <php version="^7.2 || ^8.0">
      <ext name="dom"/>
      <ext name="json"/>
      <ext name="phar"/>
      <ext name="xmlwriter"/>
    </php>
  </requires>
  <bundles>
    <component name="phar-io/version" version="" constraint="^3.0.1"/>
    <component name="symfony/serializer" version="" constraint="^5.0 || ^6.0"/>
  </bundles>
</phar>
 */

echo ManifestSerializer2::fromComposer(dirname(__DIR__) . '/tests/_fixture/manifest3.0.x-composer.lock');
/*
 * Output produced
 *
<?xml version="1.0" encoding="utf-8"?>
<phar xmlns="https://phar.io/xml/manifest/1.0">
  <requires>
    <php version="^7.2 || ^8.0">
      <ext name="dom"/>
      <ext name="json"/>
      <ext name="phar"/>
      <ext name="xmlwriter"/>
    </php>
  </requires>
  <bundles>
    <component name="phar-io/version" version="3.1.1" constraint=""/>
    <component name="symfony/deprecation-contracts" version="v2.5.0" constraint=""/>
    <component name="symfony/polyfill-ctype" version="v1.24.0" constraint=""/>
    <component name="symfony/polyfill-php80" version="v1.24.0" constraint=""/>
    <component name="symfony/serializer" version="v5.4.3" constraint=""/>
  </bundles>
</phar>
 */

echo ManifestSerializer2::fromComposer(dirname(__DIR__) . '/vendor/composer/installed.json');
/*
 * Output produced
 *
<?xml version="1.0" encoding="utf-8"?>
<phar xmlns="https://phar.io/xml/manifest/1.0">
  <requires>
    <php version="*"/>
  </requires>
  <bundles>
    <component name="phar-io/version" version="3.1.1" constraint=""/>
    <component name="symfony/deprecation-contracts" version="v2.5.0" constraint=""/>
    <component name="symfony/polyfill-ctype" version="v1.24.0" constraint=""/>
    <component name="symfony/polyfill-php80" version="v1.24.0" constraint=""/>
    <component name="symfony/serializer" version="v5.4.3" constraint=""/>
  </bundles>
</phar>
 */
