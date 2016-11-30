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

use PharIo\Version\VersionConstraint;

final class Extension extends Type {
    /**
     * @var string
     */
    private $application;

    /**
     * @var VersionConstraint
     */
    private $versionConstraint;

    /**
     * @param string            $application
     * @param VersionConstraint $versionConstraint
     */
    public function __construct($application, VersionConstraint $versionConstraint) {
        $this->application       = $application;
        $this->versionConstraint = $versionConstraint;
    }

    /**
     * @return string
     */
    public function getApplication() {
        return $this->application;
    }

    /**
     * @return VersionConstraint
     */
    public function getVersionConstraint() {
        return $this->versionConstraint;
    }

    /**
     * @return bool
     */
    public function isExtension() {
        return true;
    }

    /**
     * @param string $application
     *
     * @return bool
     */
    public function isExtensionFor($application) {
        return $this->application === $application;
    }
}
