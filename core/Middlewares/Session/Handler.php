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

namespace Mavis\Middlewares\Session;

use Mavis\Session;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Mavis\Middlewares\Session\Config;

/**
 * Session handler middleware
 * ---
 * Handles the session part of the application
 *
 * @version 0.1.0
 */
class Handler extends Session implements ServiceProviderInterface
{
    /**
     * Session raw settings
     *
     * @var array
     */
    protected $config = [];

    /**
     * __invoke magical method
     */
    public function __invoke($request, $response, $next)
    {
        return $next($request, $response);
    }

    /**
     * Class constructor
     *
     * @param array $settings
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        if (session_status() != PHP_SESSION_ACTIVE) {
            if (is_string($lifetime = $this->config->getLifetime())) {
                $this->config->setLifetime(strtotime($lifetime) - time());
            }

            session_set_cookie_params(
                $this->config->getLifetime(),
                $this->config->getPath(),
                $this->config->getDomain(),
                $this->config->getSecure(),
                $this->config->getHttponly()
            );
            session_name($this->config->getName());
            session_cache_limiter(false);
            session_start();

            if ($this->config->getAutoRefresh() == true && isset($_COOKIE[$this->config->getName()])) {
                setcookie(
                    $this->config->getName(),
                    $_COOKIE[$this->config->getName()],
                    time() + $this->config->getLifetime(),
                    $this->config->getPath(),
                    $this->config->getDomain(),
                    $this->config->getSecure(),
                    $this->config->getHttpOnly()
                );
            }
        }
    }

    /**
     * Register this middleware as a dependency
     *
     * @param  Container $container Pimple\Container object
     * @return void
     */
    public function register(Container $container)
    {
        $container['session'] = $this;
    }
}
