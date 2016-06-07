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

/*
 * A big thanks to Taylor Otwell for this file.
 * -----
 *
 * This file is a part of the Laravel Framework (v3.0)
 *  > Licensed under the MIT License
 *  > Copyright Owner: Taylor Otwell
 */

/**
 * Frequently used paths
 * ---
 * Used all over the system
 */
$paths['content'] = __DIR__;
$paths['cache'] = 'storage/cache';
$paths['uploads'] = 'storage/uploads';
$paths['logs'] = 'storage/logs';
$paths['configs'] = 'configs';
$paths['views'] = 'views';
$paths['sys'] = dirname(__DIR__);

 // --------------------------------------------------------------
 // Change to the current working directory.
 // --------------------------------------------------------------
 chdir(__DIR__);

 // --------------------------------------------------------------
// Define the directory separator for the environment.
// --------------------------------------------------------------
if (!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

 // --------------------------------------------------------------
 // Define the path to the base directory.
 // --------------------------------------------------------------
 $GLOBALS['mavis_paths']['base'] = __DIR__ . DS;

 // --------------------------------------------------------------
 // Define each constant if it hasn't been defined.
 // --------------------------------------------------------------
 foreach ($paths as $name => $path)
 {
 	if ( ! isset($GLOBALS['mavis_paths'][$name]))
 	{
 		$GLOBALS['mavis_paths'][$name] = realpath($path) . DS;
 	}
 }

 /**
  * A global path helper function.
  *
  * <code>
  *     $storage = path('storage');
  * </code>
  *
  * @param  string  $path
  * @return string
  */
 function path($path)
 {
 	return $GLOBALS['mavis_paths'][$path];
 }

 /**
  * A global path setter function.
  *
  * @param  string  $path
  * @param  string  $value
  * @return void
  */
 function set_path($path, $value)
 {
 	$GLOBALS['mavis_paths'][$path] = $value;
 }
