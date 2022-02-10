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

$xmlString = <<<STR
<phar>
    <requires>
        <php version="*">
            <ext name="dom"/>
            <ext name="json"/>
        </php>
    </requires>
</phar>
STR;
echo ManifestSerializer2::fromString($xmlString);
/*
 * Output produced
 *
<?xml version="1.0" encoding="utf-8"?>
<phar>
  <requires>
    <php version="*">
      <ext name="dom"></ext>
      <ext name="json"></ext>
    </php>
  </requires>
</phar>
 */

$jsonString = <<<STR
{"@xmlns":"https:\/\/phar.io\/xml\/manifest\/1.0","requires":{"php":{"@version":"*","ext":[{"@name":"dom"},{"@name":"json"}]}},"bundles":{"component":{"@name":"phar-io\/version","@version":"3.1.0","@constraint":"^3.0"}}}
STR;
echo ManifestSerializer2::fromString($jsonString, 'json');
/*
 * Output produced
 *
<?xml version="1.0" encoding="utf-8"?>
<phar xmlns="https://phar.io/xml/manifest/1.0">
  <requires>
    <php version="*">
      <ext name="dom"/>
      <ext name="json"/>
    </php>
  </requires>
  <bundles>
    <component name="phar-io/version" version="3.1.0" constraint="^3.0"/>
  </bundles>
</phar>
 */
