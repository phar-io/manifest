<?php declare(strict_types = 1);
namespace PharIo\Manifest;

/**
 * @covers \PharIo\Manifest\ManifestSerializer2
 */
class ManifestSerializer2Test extends \PHPUnit\Framework\TestCase {
    public function xmlFileProvider(): array {
        return [
            'application' => [__DIR__ . '/_fixture/phpunit-5.6.5.xml'],
            'library'     => [__DIR__ . '/_fixture/library.xml'],
            'extension'   => [__DIR__ . '/_fixture/extension.xml'],
        ];
    }

    public function composerFileProvider(): array {
        return [
            'composer.lock' => [__DIR__ . '/_fixture/manifest3.0.x-composer.lock'],
        ];
    }

    public function stringProvider(): array {
        return [
            'jsonString' => ['json', '{"@xmlns":"https:\/\/phar.io\/xml\/manifest\/1.0","requires":{"php":{"@version":"*","ext":[{"@name":"dom"},{"@name":"json"}]}},"bundles":{"component":{"@name":"phar-io\/version","@version":"3.1.0","@constraint":"^3.0"}}}'],
            'xmlString' => ['xml', '<phar><requires><php version="*"><ext name="dom"/><ext name="json"/></php></requires></phar>'],
        ];
    }

    /**
     * @dataProvider xmlFileProvider
     */
    public function testCanSerializeToStringFromXmlFile($expected): void {
        $this->assertXmlStringEqualsXmlFile(
            $expected,
            ManifestSerializer2::fromFile($expected)
        );
    }

    /**
     * @dataProvider composerFileProvider
     */
    public function testCanSerializeToStringFromComposerFile($filename): void {
        $expected = <<<STR
<?xml version="1.0" encoding="utf-8"?>
<phar xmlns="https://phar.io/xml/manifest/1.0">
  <requires>
    <php version="^7.2 || ^8.0">
      <ext name="dom"/>
      <ext name="json"/>
      <ext name="phar"/>
      <ext name="xmlwriter"/>
    </php>
  </requires>
  <bundles>
    <component name="phar-io/version" version="3.1.1" constraint=""/>
    <component name="symfony/deprecation-contracts" version="v2.5.0" constraint=""/>
    <component name="symfony/polyfill-ctype" version="v1.24.0" constraint=""/>
    <component name="symfony/polyfill-php80" version="v1.24.0" constraint=""/>
    <component name="symfony/serializer" version="v5.4.3" constraint=""/>
  </bundles>
</phar>
STR;
        $this->assertXmlStringEqualsXmlString(
            $expected,
            ManifestSerializer2::fromComposer($filename)
        );
    }

    /**
     * @dataProvider stringProvider
     */
    public function testCanSerializeToStringFromString($format, $string): void {
        if ('json' == $format) {
            $expected = <<<STR
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

STR;
        }

        if ('xml' == $format) {
            $expected = <<<STR
<?xml version="1.0" encoding="utf-8"?>
<phar>
  <requires>
    <php version="*">
      <ext name="dom"></ext>
      <ext name="json"></ext>
    </php>
  </requires>
</phar>

STR;
        }

        $this->assertSame(
            $expected,
            ManifestSerializer2::fromString($string, $format)
        );
    }
}
