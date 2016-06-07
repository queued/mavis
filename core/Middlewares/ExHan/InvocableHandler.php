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

use Slim\App;
use Slim\Container;
use Slim\Http\Headers;
use Slim\Http\Response;
use Exception;
use Mavis\Output\View;
use Mavis\Exception as AppException;
use Mavis\Middlewares\ExHan\Handler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Exception Handler class
 *
 * @version 0.1.0
 */
class InvocableHandler
{
    /**
     * Pimple\Container instance holder
     *
     * @var array
     */
    public $container = null;

    /**
     * Slim\App instance holder
     *
     * @var object
     */
    public $slim = null;

    /**
     * Class constructor
     */
    public function __construct(App $slim, Container $container)
    {
        $this->slim = $slim;
        $this->container = $container;
    }

    /**
     * __invoke magical method
     *
     * @param  ServerRequestInterface $request A valid PSR-7 Request object that inherits  the `ServerRequestInterface` interface
     * @param  ResponseInterface $response A valid PSR-7 Response object that inherits `ResponseInterface` interface
     * @param  Exception $exception Exception to be handled
     * @return ResponseInterface object
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Exception $exception)
    {
        // We need a new response body
        $headers = new Headers([
            'Content-Type' => 'text/html; charset=UTF-8'
        ]);
        $res = new Response(500, $headers);
        $handler = new Handler($exception);

        // Start buffering
        ob_start();

        // Create a new View instance just for this page
        $view = new View($res);
        $view->setTemplate('errors.exhan');
        $view->render($handler->getExceptionData());

        // Get the buffer output
        $content = ob_get_clean();
        return $res->write($content);
    }
}
