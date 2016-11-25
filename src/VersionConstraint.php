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

final class VersionConstraint
{
    /**
     * @var string
     */
    private $versionConstraint;

    /**
     * @param string $versionConstraint
     *
     * @throws InvalidVersionConstraintException
     */
    public function __construct($versionConstraint)
    {
        $this->ensureVersionConstraintIsValid($versionConstraint);

        $this->versionConstraint = $versionConstraint;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->versionConstraint;
    }

    /**
     * @param string $versionConstraint
     */
    private function ensureVersionConstraintIsValid($versionConstraint)
    {
    }
}
