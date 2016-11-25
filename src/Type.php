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

abstract class Type
{
    protected function __construct()
    {
    }

    public static function application()
    {
        return new Application;
    }

    public static function library()
    {
        return new Library;
    }

    public static function extension()
    {
        return new Extension;
    }

    /**
     * @return bool
     */
    public function isApplication()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isLibrary()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isExtension()
    {
        return false;
    }
}
