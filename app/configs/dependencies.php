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

$container = new Slim\Container();

// Logger as a dependency
$container['logger'] = function () {
    return new Mavis\Logger(Mavis\Logger::CHANNEL_APP);
};

// HTTP Caching
$container['httpcache'] = function () {
    return new Slim\HttpCache\CacheProvider();
};

// Views in a nutshell. JK, in a container.
$container['view'] = function ($app) {
    return new Mavis\Output\View($app->response);
};

// Session manager
$container['session'] = function () {
    return Mavis\Kernel::instance()->getSession();
};

// Jar of cookies. Grab one, pal.
$container['cookie'] = function () {
    return Mavis\Kernel::instance()->getCookieJar();
};

// Session flash messages
$container['flash'] = function () {
    return new Slim\Flash\Messages();
};

// Cross site request forgery middleware
$container['csrf'] = function () {
    return new Slim\Csrf\Guard();
};

// Access Control List
$container['acl'] = function () {
    return new SimpleAcl\Acl();
};

// Cipher helper
$container['cipher'] = function () {
    $algorithm = Mavis\Config::get('kernel.cipher.algorithm');
    $mode = Mavis\Config::get('kernel.cipher.mode');
    $hasher = Mavis\Config::get('kernel.cipher.hash_algo');
    $salt = Mavis\Config::get('kernel.cipher.salt');
    $iterations = Mavis\Config::get('kernel.cipher.iterations');

    return new Mavis\Helpers\Cipher($algorithm, $mode, $hasher, $salt, $iterations);
};

return $container;
