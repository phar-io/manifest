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

class ComposerDocument
{
    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $version;
    /** @var string */
    private $type;
    /** @var AuthorCollection */
    private $authors;
    /** @var string */
    private $license;
    /** @var Package[] */
    private $packages = [];
    /** @var Platform */
    private $platform;

    public function __construct(string $defaultId,  array $decodedData)
    {
        $this->id = (string) ($decodedData['content-hash'] ?? $defaultId);
        $this->name = (string) ($decodedData['name'] ?? 'vendor/package');
        $this->version = (string) ($decodedData['version'] ?? '0.0.0-dev');
        $this->type = (string) ($decodedData['type'] ?? 'library');
        $this->authors = new AuthorCollection();
        $this->authors->add(new Author('John Doe', new Email('john.doe@example.com')));
        $this->license = 'MIT';

        $platform = (array) $decodedData['platform'] ?? [];
        if (!isset($platform['php'])) {
            $platform['php'] = '*';
        }
        $extensions = [];
        foreach (\array_keys($platform) as $key) {
            if (\substr($key, 0, 4) === 'ext-') {
                $extensions[] = \substr($key, 4);
            }
        }
        $this->platform = new Platform($platform['php'], $extensions);

        $packages = (array) $decodedData['packages'] ?? [];
        foreach ($packages as $package) {
            $this->packages[] = new Package($package['name'] ?? 'vendor/package', $package['version'] ?? '0.0.0-dev');
        }
        $packages = (array) $decodedData['packages-dev'] ?? [];
        foreach ($packages as $package) {
            $this->packages[] = new Package($package['name'] ?? 'vendor/package', $package['version'] ?? '0.0.0-dev');
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAuthors(): AuthorCollection
    {
        return $this->authors;
    }

    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * @return Package[]
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    public function getPlatform(): Platform
    {
        return $this->platform;
    }
}
