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

namespace Mavis\Monolog\Processor;

use Mavis\Traits\LoggerTrait;
use Mavis\Logger;
use Mavis\Kernel;

/**
 * IntrospectionProcessor class
 * ---
 *
 * Modified clone of the original Monolog\Processor\IntrospectionProcessor class
 * This class has been modified to work properly with the use of Mavis\Traits\LoggerTrait
 *
 * @version 0.1.0
 */
class IntrospectionProcessor
{
    use LoggerTrait;

    private $level;

    private $skipClassesPartials;

    public function __construct($level = Logger::DEBUG, array $skipClassesPartials = array('Monolog\\'))
    {
        $this->level = static::nameToLevel($level);
        $this->skipClassesPartials = $skipClassesPartials;
    }

    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        // return if the level is not high enough
        if ($record['level'] < $this->level) {
            return $record;
        }

        $trace = debug_backtrace();

        // skip first since it's always the current method
        array_shift($trace);
        // the call_user_func call is also skipped
        array_shift($trace);

        $i = 0;

        //dd($trace);

        while (isset($trace[$i]['class'])) {
            foreach ($this->skipClassesPartials as $part) {
                if (strpos($trace[$i]['class'], $part) !== false) {
                    // This is the reason to the existance of this class
                    // I know, it sucks.
                    $i += 3;

                    // Back to normal
                    continue 2;
                }
            }
            break;
        }

        // we should have the call source now
        $record['extra'] = array_merge(
            $record['extra'],
            array(
                'file'      => isset($trace[$i-1]['file']) ? $trace[$i-1]['file'] : null,
                'line'      => isset($trace[$i-1]['line']) ? $trace[$i-1]['line'] : null,
                'class'     => isset($trace[$i]['class']) ? $trace[$i]['class'] : null,
                'function'  => isset($trace[$i]['function']) ? $trace[$i]['function'] : null,
                'environment' => Kernel::env() // Ok, since we're here, lets add this little thing
            )
        );

        return $record;
    }
}
