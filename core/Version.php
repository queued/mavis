<?php
/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2017 Mavis
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Mavis;

/**
 * Version class
 * ---
 * Version info about this app
 *
 * @version 0.1.0
 */
class Version
{
    const MAJOR  = 0;
    const MINOR  = 1;
    const PATCH  = 0;
    const PHASE  = 'dev';

    /**
     * Returns the current version when this object is treated as a string
     *
     * @return  string
     */
    public function __toString()
    {
        return static::current();
    }

    /**
     * Verify if the given version is valid
     *
     * @return bool
     */
    public static function isValid($version)
    {
        $isValid = false;
        $regexp = '{^
            (\d+)                             # major version
            (\.\d+)?                          # optional minor version
            (\.\d+)?                          # optional patch version
            (-(((alpha|beta|rc)\d*)|dev))?    # optional phase
        $}sx';
        if (preg_match($regexp, $version, $m)) {
            $isValid = true;
        }

        return $isValid;
    }

    /**
     * Returns the current version
     *
     * @return  string
     */
    public static function current()
    {
        $callback = function ($arg) {
            if ($arg === null) {
                return false;
            }

            return true;
        };

        $suffix = self::PHASE;

        return implode(
            '.',
            array_filter(
                [
                    self::MAJOR,
                    self::MINOR,
                    self::PATCH,
                ],
                $callback
            )
        )
        . (empty($suffix) ? '' : '-' . $suffix);
    }
}
