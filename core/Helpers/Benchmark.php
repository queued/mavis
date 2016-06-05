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

namespace Mavis\Helpers;

use Mavis\Helpers\Number;

/**
 * Mavis's benchmarking tool
 *
 * @version 0.1.0
 */
class Benchmark
{
    /**
     * Time pointers
     *
     * @var     array
     */
    protected static $time = [];

    /**
     * Memory pointers
     *
     * @access  protected
     * @var     array
     */
    protected static $memory = [];

    /**
     * Sets a pointer name for further debug information about page generation time and memory usage
     *
     * @param   string $name
     * @return  void
     */
    public static function start($pointer)
    {
        if (!isset(static::$time[$pointer]) && !isset(static::$memory[$pointer])) {
            static::$time[$pointer] = microtime(true);
            static::$memory[$pointer] = memory_get_usage();
        }
    }

    /**
     * Returns the memory usage for a specified pointer
     *
     * @param   string $pointer
     * @return  int
     */
    public static function memory($pointer)
    {
        if (isset(static::$memory[$pointer]) && !empty(static::$memory[$pointer])) {
            return Number::bytes(memory_get_usage() - static::$memory[$pointer]);
        }
    }

    /**
     * Returns the elapsed time for the current pointer
     *
     * @param   string $pointer
     * @return  int
     */
    public static function time($pointer)
    {
        if (isset(static::$time[$pointer]) && !empty(static::$time[$pointer])) {
            return sprintf('%01.4f', microtime(true) - static::$time[$pointer]);
        }
    }
}
