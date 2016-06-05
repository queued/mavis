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

namespace Mavis\Traits;

/**
 * Common methods for multiple Cookie-dependant objects
 *
 * @version 0.1.0
 */
trait CookieBagTrait
{
    /**
     * Cookie name
     *
     * @var string
     */
    protected $name = 'application';

    /**
     * Cookie lifetime
     *
     * @var int
     */
    protected $lifetime = 7200;

    /**
     * Cookie path
     *
     * @var string
     */
    protected $path = '/';

    /**
     * Cookie domain
     *
     * @var string
     */
    protected $domain = null;

    /**
     * HTTPS Cookie?
     *
     * @var boolean
     */
    protected $secure = false;

    /**
     * Cookie availability
     *
     * @var boolean
     */
    protected $httponly = true;

    /**
     * Cookie value
     *
     * @var mixed
     */
    protected $value = null;

    /**
     * Set the cookie value
     *
     * @param mixed $value Cookie value to be set
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the cookie value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the name for this session
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retrives the name of this session
     *
     * @param string $name Name of this session
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the session lifetime
     *
     * @return int
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * Set the session lifetime
     *
     * @param int $lifetime
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * Get the path for this session (where is the cookie going to work? '/' is the default path)
     *
     * @return null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the path for this session
     *
     * @param null $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the domain for this session
     *
     * @return null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set the domain for this session
     *
     * @param null $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Is this a secure session? (HTTPS)
     *
     * @return boolean
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * Set the security of this session (true to HTTPS, false to HTTP)
     *
     * @param boolean $secure
     */
    public function setSecure($secure)
    {
        $this->secure = $secure;
    }

    /**
     * Is this session available only to PHP?
     *
     * @return boolean
     */
    public function getHttpOnly()
    {
        return $this->httponly;
    }

    /**
     * Set the visibility of this session
     *
     * @param boolean $httponly
     */
    public function setHttpOnly($httponly)
    {
        $this->httponly = $httponly;
    }
}
