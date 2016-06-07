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

namespace Mavis\Middlewares\ExHan;

use Mavis\Kernel;
use Mavis\Middlewares\ExHan\InvocableHandler;

/**
 * Middleware class for ExHan
 *
 * @version 0.1.0
 */
class ExHanMiddleware
{
    /**
     * Class construtor
     */
    public function __construct()
    {
        // Set error handler
        set_error_handler(['Mavis\\Middlewares\\ExHan\\Handler', 'errorHandler']);

        // Set fatal error handler
        register_shutdown_function(['Mavis\\Middlewares\\ExHan\\Handler', 'fatalErrorHandler']);

        // Enable the error display
        ini_set('display_errors', 'On');

        // Disable PHP error logs
        ini_set('log_errors', 'Off');

        // Cleanup cache
        /*$namespaces = Directory::scan($folder);
        if (count($namespaces) > 0) {
            foreach ($namespaces as $namespace) {
                Directory::delete($folder . DS . $namespace);
            }
        }*/
    }

    public function __invoke($request, $response, $next)
    {
        $slim = Kernel::instance()->getSlim();
        $container = $slim->getContainer();
        $handler = new InvocableHandler($slim, $container);

        $container['errorHandler'] = function() use ($handler) {
            return $handler;
        };

        $container['exhan'] = $handler;

        return $next($request, $response);
    }
}
