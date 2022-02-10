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

echo ManifestSerializer2::fromFile(__DIR__ . '/../tests/_fixture/manifest.xml');
/*
 * Output produced
 *
<?xml version="1.0" encoding="utf-8"?>
<phar xmlns="https://phar.io/xml/manifest/1.0">
  <contains name="some/library" version="1.0.0" type="library"></contains>
  <copyright>
    <author name="Reiner Zufall" email="reiner@zufall.de"></author>
    <license type="BSD-3-Clause" url="https://domain.tld/LICENSE"></license>
  </copyright>
  <requires>
    <php version="7.0"></php>
  </requires>
</phar>
 */
