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

namespace Mavis\Middlewares\Errors;

use Mavis\Kernel;

/**
 * 500 Internal Server Error middleware
 *
 * @version 0.1.0
 */
class ServerError
{
    public function __construct()
    {
        $slim = Kernel::instance()->getSlim();
        $container = $slim->getContainer();
        $container['errorHandler'] = $this;

        // Set error handler
        set_error_handler(['Mavis\\Middlewares\\Errors\\ServerError', 'errorHandler']);

        // Set fatal error handler
        register_shutdown_function(['Mavis\\Middlewares\\Errors\\ServerError', 'errorHandler']);

        // Supress the error displaing
        ini_set('display_errors', 'Off');

        // Enable the PHP to log errors
        ini_set('log_errors', 'On');

        // Set the PHP log file
        ini_set('error_log', path('storage') . 'logs' . DS . 'error.log');
    }

    public function __invoke($request, $response, $next)
    {
        // FIXME: Display error page to production systems (data-less)

        dde('Something went to shit.');
        return $next($request, $response);
    }

    /**
     * Do the handling part of all errors when this turns into a production environment
     *
     * @param  \Exception $exception [description]
     * @return bool
     */
    public static function errorHandler($exception)
    {
        // FIXME: Display error page

        // Don't execute default PHP error handler
        return true;
    }
}
