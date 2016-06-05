<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2017 Mavis
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Mavis;

use Mavis\Helpers\Collection;

/**
 * Config class
 * ---
 * Configuration loader
 *
 * @version 0.1.0
 */
class Config
{
    /**
     * Extension to be used
     */
    const FILE_EXTENSION = '.php';

    /**
     * Multiton holder for this object
     *
     * @var array[object]
     */
    protected static $instance = [];

    /**
     * Configuration stack
     *
     * @var array
     */
    protected static $stack = [];

    /**
     * Get the requested configuration entry
     *
     * @param string $identifier A valid string to be used as identifier
     * @return mixed
     */
    public static function get($identifier, $default = null)
    {
        $key = $identifier;

        // Initiates everything
        static::load();

        // So, you need to get the whole stack? No problem.
        if ($key == '*') {
            return static::$stack;
        }

        return array_get(static::$stack, $key, $default);
    }

    public static function set($identifier, $value)
    {
        $key = $identifier;

        // Initiates everything
        static::load();

        array_set($key, static::$stack);
    }

    public static function exists($identifier)
    {
        $key = $identifier;

        // Initiates everything
        static::load();

        return array_exists(static::$stack, $key);
    }

    public static function remove($identifier)
    {
        $key = $identifier;

        // Initiates everything
        static::load();

        array_remove(static::$stack, $key);
    }

    /**
     * Set this object up using the provided file configs
     *
     * @return void
     */
    protected static function load()
    {
        $configs = require path('configs') . 'app' . Config::FILE_EXTENSION;

        // Pushes the loaded configs from the given identifier
        static::$stack = (new Collection($configs))->all();
    }
}
