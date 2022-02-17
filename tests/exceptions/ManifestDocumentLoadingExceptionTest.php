<?php declare(strict_types = 1);
/*
 * This file is part of PharIo\Manifest.
 *
 * Copyright (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de> and contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace PharIo\Manifest;

use DOMDocument;
use LibXMLError;
use function libxml_get_errors;
use function libxml_use_internal_errors;

class ManifestDocumentLoadingExceptionTest extends \PHPUnit\Framework\TestCase {
    public function testXMLErrorsCanBeRetrieved(): void {
        $dom  = new DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $dom->loadXML('<?xml version="1.0" ?><broken>');
        $exception = new ManifestDocumentLoadingException(libxml_get_errors());
        libxml_use_internal_errors($prev);

        $this->assertContainsOnlyInstancesOf(LibXMLError::class, $exception->getLibxmlErrors());
    }
}
