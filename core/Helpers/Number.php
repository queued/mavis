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

/**
 * Support class to work with numbers
 *
 * @author All Unser Miranda <miranda@codesans.com>
 * @version 0.1.0
 */
class Number
{
    /**
     * Convert bytes in 'KB', 'MB', 'GB', 'TB', 'PB' or 'EB'
     *
     *  <code>
     *      echo Number::bytes(10000);
     *  </code>
     *
     * @param  integer $size Data to convert
     * @return string
     */
    public static function bytes($size)
    {
        // Redefine vars
        $size = (int) $size;
        $unit = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * Converts a number into a more readable human-type number.
     *
     *  <code>
     *      echo Number::quantity(7000); // 7K
     *      echo Number::quantity(7500); // 8K
     *      echo Number::quantity(7500, 1); // 7.5K
     *  </code>
     *
     * @param   integer $num      Num to convert
     * @param   integer $decimals Decimals
     * @return  string
     */
    public static function quantity($num, $decimals = 0)
    {
        // Redefine vars
        $num      = (int) $num;
        $decimals = (int) $decimals;

        if ($num >= 1000 && $num < 1000000) {
            return sprintf('%01 . ' . $decimals . 'f', (sprintf('%01.0f', $num) / 1000)) . 'K';
        } elseif ($num >= 1000000 && $num < 1000000000) {
            return sprintf('%01.' . $decimals . 'f', (sprintf('%01.0f', $num) / 1000000)) . 'M';
        } elseif ($num >= 1000000000) {
            return sprintf('%01.' . $decimals . 'f', (sprintf('%01.0f', $num) / 1000000000)) . 'B';
        }

        return $num;
    }

    /**
     * Checks if the value is between the minimum and maximum (min & max included).
     *
     *  <code>
     *      if (Number::between(2, 10, 5)) {
     *          // do something...
     *      }
     *  </code>
     *
     * @param  float  $minimum  The minimum.
     * @param  float  $maximum  The maximum.
     * @param  float  $value    The value to validate.
     * @return boolean
     */
    public static function between($minimum, $maximum, $value)
    {
        return ((float) $value >= (float) $minimum && (float) $value <= (float) $maximum);
    }

    /**
     * Checks the value for an even number.
     *
     *  <code>
     *      if (Number::even(2)) {
     *          // do something...
     *      }
     *  </code>
     *
     * @param  integer $value The value to validate.
     * @return boolean
     */
    public static function even($value)
    {
        return (((int) $value % 2) == 0);
    }

    /**
     * Checks the value for an odd number.
     *
     *  <code>
     *      if (Number::odd(2)) {
     *          // do something...
     *      }
     *  </code>
     *
     * @param   integer $value The value to validate.
     * @return  boolean
     */
    public static function odd($value)
    {
        return !static::even((int) $value);
    }

     /**
     * Checks if the value is greather than a given minimum.
     *
     *  <code>
     *      if (Number::greaterThan(2, 10)) {
     *          // do something...
     *      }
     *  </code>
     *
     * @param  float   $minimum The minimum as a float.
     * @param  float   $value   The value to validate.
     * @return boolean
     */
    public static function greaterThan($minimum, $value)
    {
        return ((float) $value > (float) $minimum);
    }

     /**
     * Checks if the value is smaller than a given maximum.
     *
     *  <code>
     *      if (Number::smallerThan(2, 10)) {
     *          // do something...
     *      }
     *  </code>
     *
     * @param  integer $maximum The maximum.
     * @param  integer $value   The value to validate.
     * @return boolean
     */
    public static function smallerThan($maximum, $value)
    {
        return ((int) $value < (int) $maximum);
    }

    /**
     * Checks if the value is not greater than or equal a given maximum.
     *
     *  <code>
     *      if (Number::maximum(2, 10)) {
     *          // do something...
     *      }
     *  </code>
     *
     * @param  integer $maximum  The maximum.
     * @param  integer $value    The value to validate.
     * @return boolean
     */
    public static function maximum($maximum, $value)
    {
        return ((int) $value <= (int) $maximum);
    }

     /**
     * Checks if the value is greater than or equal to a given minimum.
     *
     *  <code>
     *      if (Number::minimum(2, 10)) {
     *          // do something...
     *      }
     *  </code>
     *
     * @param  integer $minimum The minimum.
     * @param  integer $value   The value to validate.
     * @return boolean
     */
    public static function minimum($minimum, $value)
    {
        return ((int) $value >= (int) $minimum);
    }
}
