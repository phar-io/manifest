<?php declare(strict_types = 1);
/*
 * This file is part of PharIo\Manifest.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PharIo\Manifest;

use Symfony\Component\Serializer\Encoder\ChainDecoder;
use Symfony\Component\Serializer\Encoder\ChainEncoder;
use Symfony\Component\Serializer\Encoder\ContextAwareDecoderInterface;
use Symfony\Component\Serializer\Encoder\ContextAwareEncoderInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class ManifestSerializer2 implements SerializerInterface {
    /** @var ContextAwareEncoderInterface */
    private $encoder;
    /** @var ContextAwareDecoderInterface */
    private $decoder;

    /**
     * @param array<EncoderInterface|DecoderInterface> $codecs
     */
    public function __construct(array $codecs) {
        $decoders = [];
        $encoders = [];
        foreach ($codecs as $encoder) {
            if ($encoder instanceof EncoderInterface) {
                $encoders[] = $encoder;
            }
            if ($encoder instanceof DecoderInterface) {
                $decoders[] = $encoder;
            }
            if (!($encoder instanceof EncoderInterface || $encoder instanceof DecoderInterface)) {
                throw new InvalidArgumentException(
                    \sprintf('The class "%s" neither implements "%s" nor "%s".',
                        \get_debug_type($encoder),
                        EncoderInterface::class,
                        DecoderInterface::class
                    )
                );
            }
        }
        $this->encoder = new ChainEncoder($encoders);
        $this->decoder = new ChainDecoder($decoders);
    }

    /**
     * Checks whether the serializer can encode to given format.
     */
    protected function supportsEncoding(string $format, array $context = []): bool {
        return $this->encoder->supportsEncoding($format, $context);
    }

    /**
     * Checks whether the deserializer can decode from given format.
     */
    protected function supportsDecoding(string $format, array $context = []): bool {
        return $this->decoder->supportsDecoding($format, $context);
    }

    /**
     * Serializes data in the appropriate format.
     */
    final public function serialize($data, string $format, array $context = []): string {
        if (!$this->supportsEncoding($format, $context)) {
            throw new NotEncodableValueException(
                \sprintf('Serialization for the format "%s" is not supported.', $format)
            );
        }

        $context = \array_merge($context, [
            XmlEncoder::FORMAT_OUTPUT => true,
            XmlEncoder::ENCODING => 'utf-8',
            XmlEncoder::ROOT_NODE_NAME => 'phar',
        ]);
        return $this->encoder->encode($data, $format, $context);
    }

    /**
     * Deserializes data into the given type.
     */
    final public function deserialize($data, string $type, string $format, array $context = []) {
        if (!$this->supportsDecoding($format, $context)) {
            throw new NotEncodableValueException(
                \sprintf('Deserialization for the format "%s" is not supported.', $format)
            );
        }

        $context = \array_merge($context, [
            XmlEncoder::FORMAT_OUTPUT => true,
            XmlEncoder::ENCODING => 'utf-8',
            XmlEncoder::ROOT_NODE_NAME => 'phar',
        ]);
        return $this->decoder->decode($data, $format, $context);
    }

    /**
     * Builds an XML manifest from contents of a file.
     */
    public static function fromFile(string $filename, string $format = 'xml', array $context = []): string {
        if (!\file_exists($filename)) {
            throw new ManifestDocumentException(
                \sprintf('File "%s" not found', $filename)
            );
        }

        $xmlString = \file_get_contents($filename);
        return self::fromString($xmlString, $format, $context);
    }

    /**
     * Builds an XML manifest from a string in the given format.
     */
    public static function fromString(string $data, string $format = 'xml', array $context = []): string {
        $codecs = [];
        $codecs[] = new XmlEncoder();
        if ('json' === $format) {
            $codecs[] = new JsonEncoder();
        }

        $context = \array_merge($context, [
            XmlEncoder::FORMAT_OUTPUT => true,
            XmlEncoder::ENCODING => 'utf-8',
            XmlEncoder::ROOT_NODE_NAME => 'phar',
            XmlEncoder::TYPE_CAST_ATTRIBUTES => false,
        ]);

        $data = (new self($codecs))->deserialize($data, '', $format, $context);
        return self::fromArray($data, 'xml', $context);
    }

    /**
     * Builds an XML manifest from a PHP array structured compatible with the Symfony/Serializer component.
     */
    public static function fromArray(array $data, string $format = 'xml', array $context = []): string {
        $codecs = [];
        $codecs[] = new XmlEncoder();
        if ('json' === $format) {
            $codecs[] = new JsonEncoder();
        }
        return (new self($codecs))->serialize($data, $format, $context);
    }

    /**
     * Builds an XML manifest from Composer data source.
     */
    public static function fromComposer(string $filename, string $format = 'xml', array $context = []): string {
        if (!\file_exists($filename)) {
            throw new ManifestDocumentException(
                \sprintf('File "%s" not found', $filename)
            );
        }

        $jsonString = \file_get_contents($filename);

        return self::fromArray(self::composerData($jsonString), $format, $context);
    }

    /**
     * Decodes contents of either "composer.json", "composer.lock" or "vendor/composer/installed.json" files.
     *
     * @return array<string, mixed>
     */
    private static function composerData(string $jsonString): array {
        $encoder = new JsonEncoder();

        $context = [];

        $decodedData = $encoder->decode($jsonString, 'json', $context); //var_dump($decodedData);

        if (isset($decodedData['require'])) {
            // JSON source seems to be a `composer.json`
            $platform = (array) $decodedData['require'];
            $packages = [];
        } else {
            $platform = (array) ($decodedData['platform'] ?? []);
            $packages = (array) ($decodedData['packages'] ?? []);
            $packagesDev = (array) ($decodedData['packages-dev'] ?? []);
            $packages = \array_merge($packages, $packagesDev);
        }

        if (!isset($platform['php'])) {
            $platform['php'] = '*';
        }
        $extensions = [];
        foreach (\array_keys($platform) as $key) {
            if (\substr($key, 0, 4) === 'ext-') {
                $extensions[] = ['@name' => \substr($key, 4)];
            } elseif ('php' !== $key) {
                // JSON source seems to be a `composer.json`
                $packages[] = ['name' => $key, 'constraint' => $platform[$key], 'version' => ''];
            }
        }

        $bundles = [];
        foreach ($packages as $package) {
            $bundles[] = [
                '@name' => $package['name'] ?? 'vendor/package',
                '@version' => $package['version'] ?? '0.0.0-dev',
                '@constraint' => $package['constraint'] ?? '',
            ];
        }

        $data = [
            '@xmlns' => ManifestDocument::XMLNS,
            'requires' => [
                'php' => [
                    '@version' => $platform['php'],
                ]
            ]
        ];
        if (!empty($extensions)) {
            $data['requires']['php']['ext'] = $extensions;
        }
        if (!empty($bundles)) {
            $data['bundles']['component'] = $bundles;
        }

        return $data;
    }
}
