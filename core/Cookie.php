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

use Mavis\Traits\CookieBagTrait as CookieBag;

/**
 * Cookie object
 *
 * @version 0.1.0
 */
class Cookie
{
    use CookieBag;

    /**
     * Class construtor
     *
     * @param array $options Associative array with session settings
     */
    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Transforms this object into a `Set-Cookie:` compatible header
     *
     * @return string
     */
    public function __toString()
    {
        $result = urlencode($this->name) . '=' . urlencode($this->value);

        if (isset($this->domain)) {
            $result .= '; domain=' . $this->domain;
        }

        if (isset($this->path)) {
            $result .= '; path=' . $this->path;
        }

        if (isset($this->lifetime)) {
            if (is_string($this->lifetime)) {
                $timestamp = strtotime($this->lifetime);
            } else {
                $timestamp = (int) $this->lifetime;
            }

            if ($timestamp !== 0) {
                $result .= '; expires=' . gmdate('D, d-M-Y H:i:s e', $timestamp);
            }
        }

        if (isset($this->secure) && $this->secure == true) {
            $result .= '; secure';
        }

        if (isset($this->httponly) && $this->httponly == true) {
            $result .= '; HttpOnly';
        }

        return $result;
    }
}
