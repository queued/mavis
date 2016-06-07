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

namespace Mavis\Middlewares\Cookies;

use Mavis\Kernel;
use Mavis\Cookie;
use Mavis\Helpers\Collection;

/**
 * Cookie jar middleware
 *
 * @version 0.1.0
 */
class Jar
{
    /**
     * Slim\App instance holder
     *
     * @var object
     */
    protected $slim = null;

    /**
     * Slim\Http\Response instance holder
     *
     * @var object
     */
    protected $response = null;

    /**
     * Mavis\Helpers\Cipher instance holder
     *
     * @var object
     */
    protected $cipher = null;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->slim = Kernel::instance()->getSlim();
        $container = $this->slim->getContainer();

        $this->cipher = $container->get('cipher');
        $this->response = $container->get('response');
    }

    /**
     * __invoke magical method
     */
    public function __invoke($request, $response, $next)
    {
        return $next($request, $response);
    }

    /**
     * Sets a new cookie
     *
     * @param  Cookie $cookie A valid Mavis\Middlewares\Cookies\Cookie instance
     * @return boolean
     */
    public function set(Cookie $cookie)
    {
        // Do setcookie()
        return $this->setCookie($cookie);
    }

    /**
     * Get the specified cookie
     *
     * @param string $cookie Cookie's name
     * @param mixed $default Default value if the specified cookie does not exists
     * @return mixed
     */
    public function get($cookie, $default = null)
    {
        if (isset($_COOKIE[$cookie])) {
            $raw = $this->decryptCookie($_COOKIE[$cookie]);
            $value = $this->cipher->decrypt($raw['value']);
            $hash = $this->cipher->decrypt($raw['hash']);

            if ($this->cipher->match($value, $hash)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Deletes the given cookie from the stack and the client's browser
     *
     * @param string $cookie Cookie's name
     * @return boolean
     */
    public function delete($cookie)
    {
        return $this->setCookie($cookie, true);
    }

    /**
     * Does the given cookie's name exists?
     *
     * @param string $cookie Cookie name
     * @return boolean
     */
    public function exists($cookie)
    {
        return ($this->get($cookie) !== null) ?: false;
    }

    /**
     * Alias for `exists` method
     *
     * @param  string $cookie Cookie name
     * @return boolean
     */
    public function has($cookie)
    {
        return $this->exists($cookie);
    }

    /**
     * Clear all cookie data.
     */
    public function clear()
    {
        $_COOKIE = [];
    }

    /**
     * Set the cookie
     *
     * @param Cookie $cookie A valid Mavis\Middlewares\Cookies\Cookie instance
     * @param boolean $delete Delete this cookie?
     * @return boolean
     */
    protected function setCookie(Cookie $cookie, $delete = false)
    {
        $value = $this->encryptCookie($cookie->getValue());
        $lifetime = (is_string($cookie->getLifetime())) ? strtotime($cookie->getLifetime()) - time() : $cookie->getLifetime();

        return setcookie(
            $cookie->getName(),
            $value,
            ($delete) ? time() - $lifetime : time() + $lifetime,
            $cookie->getPath(),
            $cookie->getDomain(),
            $cookie->getSecure(),
            $cookie->getHttpOnly()
        );
    }

    /**
     * Encrypt the given $value to be safely stored as a cookie
     *
     * @param string $value Value to be encrypted
     * @return string
     */
    protected function encryptCookie($value)
    {
        $data['value'] = $this->cipher->encrypt($value);
        $data['hash'] = $this->cipher->encrypt($this->cipher->hash($value));

        return base64_encode(json_encode($data));
    }

    /**
     * Decrypts the given string to its real value
     *
     * @param string $crypted Encrypted string to be decrypted
     * @return string
     */
    protected function decryptCookie($encrypted)
    {
        return json_decode(base64_decode($encrypted), true);
    }
}
