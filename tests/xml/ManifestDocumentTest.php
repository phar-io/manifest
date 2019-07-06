<?php declare(strict_types = 1);
namespace PharIo\Manifest;

class ManifestDocumentTest extends \PHPUnit\Framework\TestCase {
    public function testThrowsExceptionWhenFileDoesNotExist(): void {
        $this->expectException(ManifestDocumentException::class);
        ManifestDocument::fromFile('/does/not/exist');
    }

    public function testCanBeCreatedFromFile(): void {
        $this->assertInstanceOf(
            ManifestDocument::class,
            ManifestDocument::fromFile(__DIR__ . '/../_fixture/phpunit-5.6.5.xml')
        );
    }

    public function testCaneBeConstructedFromString(): void {
        $content = \file_get_contents(__DIR__ . '/../_fixture/phpunit-5.6.5.xml');
        $this->assertInstanceOf(
            ManifestDocument::class,
            ManifestDocument::fromString($content)
        );
    }

    public function testThrowsExceptionOnInvalidXML(): void {
        $this->expectException(ManifestDocumentLoadingException::class);
        ManifestDocument::fromString('<?xml version="1.0" ?><root>');
    }

    public function testLoadingDocumentWithWrongRootNameThrowsException(): void {
        $this->expectException(ManifestDocumentException::class);
        ManifestDocument::fromString('<?xml version="1.0" ?><root />');
    }

    public function testLoadingDocumentWithWrongNamespaceThrowsException(): void {
        $this->expectException(ManifestDocumentException::class);
        ManifestDocument::fromString('<?xml version="1.0" ?><phar xmlns="foo:bar" />');
    }

    public function testContainsElementCanBeRetrieved(): void {
        $this->assertInstanceOf(
            ContainsElement::class,
            $this->loadFixture()->getContainsElement()
        );
    }

    public function testRequiresElementCanBeRetrieved(): void {
        $this->assertInstanceOf(
            RequiresElement::class,
            $this->loadFixture()->getRequiresElement()
        );
    }

    public function testCopyrightElementCanBeRetrieved(): void {
        $this->assertInstanceOf(
            CopyrightElement::class,
            $this->loadFixture()->getCopyrightElement()
        );
    }

    public function testBundlesElementCanBeRetrieved(): void {
        $this->assertInstanceOf(
            BundlesElement::class,
            $this->loadFixture()->getBundlesElement()
        );
    }

    public function testThrowsExceptionWhenContainsIsMissing(): void {
        $this->expectException(ManifestDocumentException::class);
        $this->loadEmptyFixture()->getContainsElement();
    }

    public function testThrowsExceptionWhenCopyirhgtIsMissing(): void {
        $this->expectException(ManifestDocumentException::class);
        $this->loadEmptyFixture()->getCopyrightElement();
    }

    public function testThrowsExceptionWhenRequiresIsMissing(): void {
        $this->expectException(ManifestDocumentException::class);
        $this->loadEmptyFixture()->getRequiresElement();
    }

    public function testThrowsExceptionWhenBundlesIsMissing(): void {
        $this->expectException(ManifestDocumentException::class);
        $this->loadEmptyFixture()->getBundlesElement();
    }

    public function testHasBundlesReturnsTrueWhenBundlesNodeIsPresent(): void {
        $this->assertTrue(
            $this->loadFixture()->hasBundlesElement()
        );
    }

    public function testHasBundlesReturnsFalseWhenBundlesNoNodeIsPresent(): void {
        $this->assertFalse(
            $this->loadEmptyFixture()->hasBundlesElement()
        );
    }

    private function loadFixture() {
        return ManifestDocument::fromFile(__DIR__ . '/../_fixture/phpunit-5.6.5.xml');
    }

    private function loadEmptyFixture() {
        return ManifestDocument::fromString(
            '<?xml version="1.0" ?><phar xmlns="https://phar.io/xml/manifest/1.0" />'
        );
    }
}
