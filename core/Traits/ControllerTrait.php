<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2015 Code Sans
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

namespace Mavis\Traits;

/**
 * Controller trait
 * ---
 * Provides some bootstrapping to Mavis\Controllers
 *
 * @version 0.1.0
 */
trait ControllerTrait
{
    /**
     * {@inheritDoc}
     */
    public function setTemplate($name)
    {
        $this->template = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        $array = [];

        foreach ($this->data as $data => $value) {
            // Objects can't be evaluated when parsing the template
            if (is_object($value)) {
                // Skip to next iteration
                continue;
            } else {
                $array[$data] = $value;
            }
        }

        return $array;
    }
}
