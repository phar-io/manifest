<?php declare(strict_types=1);
/*
 * This file is part of PharIo\Manifest.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PharIo\Manifest;

class Platform
{
    /** @var string */
    private $phpConstraint;
    /** @var string[] */
    private $extensions;

    /**
     * @param string $phpConstraint
     * @param string[] $extensions
     */
    public function __construct(string $phpConstraint, array $extensions)
    {
        $this->phpConstraint = $phpConstraint;
        $this->extensions = $extensions;
    }

    public function getPhp(): string
    {
        return $this->phpConstraint;
    }

    /**
     * @return string[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}