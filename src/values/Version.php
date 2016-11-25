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

final class Version {
    /**
     * @var string
     */
    private $version;

    /**
     * @var int
     */
    private $major;

    /**
     * @var int
     */
    private $minor;

    /**
     * @var int
     */
    private $patch;

    /**
     * @var string
     */
    private $releaseType = 'stable';

    /**
     * @var int
     */
    private $releaseTypeCount = 0;

    /**
     * @param string $version
     *
     * @throws InvalidVersionException
     */
    public function __construct($version) {
        $this->ensureVersionIsValid($version);

        $this->version = $version;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->version;
    }

    /**
     * @return int
     */
    public function getMajorVersion() {
        return $this->major;
    }

    /**
     * @return int
     */
    public function getMinorVersion() {
        return $this->minor;
    }

    /**
     * @return int
     */
    public function getPatchLevel() {
        return $this->patch;
    }

    /**
     * @return string
     */
    public function getReleaseType() {
        return $this->releaseType;
    }

    /**
     * @return int
     */
    public function getReleaseTypeCount() {
        return $this->releaseTypeCount;
    }

    /**
     * @return bool
     */
    public function isStableVersion() {
        return $this->releaseType === 'stable';
    }

    /**
     * @return bool
     */
    public function isBetaVersion() {
        return $this->releaseType === 'beta';
    }

    /**
     * @return bool
     */
    public function isAlphaVersion() {
        return $this->releaseType === 'alpha';
    }

    /**
     * @return bool
     */
    public function isPatchVersion() {
        return $this->releaseType === 'patch';
    }

    /**
     * @return bool
     */
    public function isDevelopmentVersion() {
        return $this->releaseType === 'dev';
    }

    /**
     * @param string $version
     *
     * @throws InvalidVersionException
     */
    private function ensureVersionIsValid($version) {
        $regex = '/^
            (?<Major>(0|(?:[1-9][0-9]*)))
            \\.
            (?<Minor>(0|(?:[1-9][0-9]*)))
            \\.
            (?<Patch>(0|(?:[1-9][0-9]*)))
            (?:
                -
                (?<ReleaseType>(?:(dev|beta|b|RC|alpha|a|patch|p)))
                (?:
                    (?<ReleaseTypeCount>[0-9])
                )?
            )?       
        $/x';

        if (preg_match($regex, $version, $matches) !== 1) {
            throw new InvalidVersionException(
                sprintf("Version string '%s' does not follow SemVer semantics", $version)
            );
        }

        $this->initValues($matches);
    }

    /**
     * @param string[] $matches
     */
    private function initValues(array $matches) {
        $this->major = $matches['Major'];
        $this->minor = $matches['Minor'];
        $this->patch = $matches['Patch'];

        if (!isset($matches['ReleaseType'])) {
            return;
        }

        if (strlen($matches['ReleaseType']) === 1) {
            $expanded               = [
                'a' => 'alpha',
                'b' => 'beta',
                'p' => 'patch'
            ];
            $matches['ReleaseType'] = $expanded[$matches['ReleaseType']];
        }

        $this->releaseType = $matches['ReleaseType'];

        if (!isset($matches['ReleaseTypeCount'])) {
            $this->releaseTypeCount = 1;

            return;
        }

        $this->releaseTypeCount = $matches['ReleaseTypeCount'];
    }
}
