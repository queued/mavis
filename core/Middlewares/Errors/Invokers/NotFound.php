<?php
/**
 * This file is part of the Mavis
 *
 * Created by PhpStorm
 */

namespace Mavis\Middlewares\Errors\Invokers;

use Mavis\Output\View;
use Slim\Container;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;

class NotFound
{
    protected $container = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response)
    {
        $data = [
            'uri' => $request->getUri()->getPath(),
            'contact' => $this->container->router->pathFor('contact')
        ];

        // We need a new response body
        $headers = new Headers([
            'Content-Type' => 'text/html; charset=UTF-8'
        ]);
        $res = new Response(404, $headers);

        // Start buffering
        ob_start();

        $view = new View($res);
        $view->setTemplate('errors.404');
        $view->render($data);

        // Get the buffer output
        $content = ob_get_clean();
        return $res->write($content);
    }
}
