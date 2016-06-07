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

if (!function_exists('dde')) {
    function dde($data = [])
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';

        exit(1);
    }
}

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array $array Array reference
     * @param string $key Key to search for
     * @param mixed $default Default value if no match is found
     * @return mixed
     */
    function array_get(array $array, $key, $default = null)
    {
    	if (is_null($key)) {
            return $array;
        }

    	if (isset($array[$key])) {
            return $array[$key];
        }

    	foreach (explode('.', $key) as $segment) {
    		if (!array_key_exists($segment, $array)) {
    			return $default;
    		}

    		$array = $array[$segment];
    	}

    	return $array;
    }
}

if (!function_exists('array_set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param array $array Array reference
     * @param string $key Key to be set
     * @param mixed $value Value for the given $key
     * @return array
     */
    function array_set(array &$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

    	$keys = explode('.', $key);

    	while (count($keys) > 1) {
    		$key = array_shift($keys);

    		// If the key doesn't exist at this depth, we will just create an empty array
    		// to hold the next value, allowing us to create the arrays to hold final
    		// values at the correct depth. Then we'll keep digging into the array.
    		if (!isset($array[$key]) || !is_array($array[$key]))
    		{
    			$array[$key] = [];
    		}

    		$array =& $array[$key];
    	}

    	$array[array_shift($keys)] = $value;

    	return $array;
    }
}

if (!function_exists('array_exists')) {
    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param array $array Array reference
     * @param string $key Key to search for
     * @return bool
     */
    function array_exists(array $array, $key)
    {
        if (empty($array) || is_null($key)) {
             return false;
        }

    	if (array_key_exists($key, $array)) {
            return true;
        }

    	foreach (explode('.', $key) as $segment) {
    		if (!array_key_exists($segment, $array)) {
    			return false;
    		}

    		$array = $array[$segment];
    	}

    	return true;
    }

}

if (!function_exists('array_remove')) {
    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array $array Array reference
     * @param array|string $keys Keys to be removed
     * @return void
     */
    function array_remove(array &$array, $keys)
    {
        $original =& $array;
    	foreach ((array) $keys as $key) {
    		$parts = explode('.', $key);

    		while (count($parts) > 1) {
    			$part = array_shift($parts);

    			if (isset($array[$part]) && is_array($array[$part])) {
    				$array =& $array[$part];
    			}
    		}

    		unset($array[array_shift($parts)]);

    		// clean up after each pass
    		$array =& $original;
    	}
    }
}
