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

class ManifestLoader {
    /**
     * @param string $filename
     *
     * @return Manifest
     */
    public static function fromFile($filename) {
        return (new ManifestDocumentMapper())->map(
            ManifestDocument::fromFile($filename)
        );
    }

    /**
     * @param string $filename
     *
     * @return Manifest
     */
    public static function fromPhar($filename) {
        return self::fromFile('phar:///' . $filename . '/manifest.xml');
    }

    /**
     * @param string $manifest
     *
     * @return Manifest
     */
    public static function fromString($manifest) {
        return (new ManifestDocumentMapper())->map(
            ManifestDocument::fromString($manifest)
        );
    }
}
