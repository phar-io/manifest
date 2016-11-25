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

final class Version
{
    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     *
     * @throws InvalidVersionException
     */
    public function __construct($version)
    {
        $this->ensureVersionIsValid($version);

        $this->version = $version;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    private function ensureVersionIsValid($version)
    {
    }
}
