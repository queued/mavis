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

use Mavis\Events\OnLoadMiddlewareEvent;
use Mavis\Events\OnNotFoundEvent;
use Mavis\Events\OnStartEvent;
use Mavis\Events\OnFinishEvent;
use Evenement\EventEmitter;
use Mavis\Config;
use Mavis\Logger;
use Mavis\Middlewares\ExHan\ExHanMiddleware;
use Mavis\Middlewares\Errors\ServerError;
use Mavis\Middlewares\Errors\NotAllowed;
use Mavis\Middlewares\Errors\Forbidden;
use Mavis\Middlewares\Errors\NotFound;
use Mavis\Middlewares\Session\Handler;
use Mavis\Middlewares\Session\Config as SessionConfig;
use Mavis\Middlewares\Cookies\Jar as CookieJar;
use Slim\App;

/**
 * Kernel class
 * ---
 * The system core
 *
 * @version 0.1.0
 */
class Kernel
{
    /**
     * Available environments
     */
    const PRODUCTION = 'production';
    const DEVELOPMENT = 'development';

    /**
     * Enviroment for this setup
     *
     * @var string
     */
    private static $env = Kernel::PRODUCTION;

    /**
     * Singleton holder
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Slim\App instance holder
     *
     * @var object
     */
    public $slim = null;

    /**
     * Evenement\EventEmitter instance holder
     *
     * @var object
     */
    public $emitter = null;

    /**
     * Array with a few Mavis\Logger instances
     *
     * @var array
     */
    public $logger = [];

    /**
     * Mavis\Middlewares\Session\Handler instance
     *
     * @var object
     */
    public $session = null;

    /**
     * Mavis\Middlewares\Cookies\Jar instance holder
     *
     * @var object
     */
    public $cookies = null;

    /**
     * Class constructor.
     *
     * @return object
     */
    public function __construct(array $args = [])
    {
        $this->emitter = new EventEmitter();

        // Environment priority: $_SERVER > CONSTANT
        if (defined('MAVIS_ENV') && MAVIS_ENV !== static::PRODUCTION) {
            static::env(MAVIS_ENV);
        } elseif (isset($_SERVER['MAVIS_ENV'])) {
            static::env($_SERVER['MAVIS_ENV']);
        }
    }

    /**
     * Retrieves the Slim\App instance for this request
     *
     * @return object
     */
    public function getSlim()
    {
        return $this->slim;
    }

    /**
     * Retrieves the Evenement\EventEmitter instance for this request
     *
     * @return object
     */
    public function getEmitter()
    {
        return $this->emitter;
    }

    /**
     * Retrieves the Mavis\Logger instance for this request
     *
     * @return object
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Retrieves the Mavis\Middlewares\Session\Handler instance for this request
     *
     * @return object
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Retrieves Mavis\Middlewares\Cookies\Jar instance for this request
     *
     * @return object
     */
    public function getCookieJar()
    {
        return $this->cookies;
    }

    /**
     * Register all default events
     *
     * @return void
     */
    protected function registerEvents()
    {
        $this->emitter->on('app.start', new OnStartEvent($this->logger['kernel'], Config::get('site.name')));
        $this->emitter->on('app.finish', new OnFinishEvent($this->logger['kernel'], Config::get('site.name')));
        $this->emitter->on('app.middleware.load', new OnLoadMiddlewareEvent($this->logger['slim']));
    }

    /**
     * Bootstrap the application by filling all the needed variables and RUN!
     *
     * @param array $args Argument array from CLI
     * @return void
     */
    public function run(array $args = [])
    {
        $container = require path('configs') . 'dependencies.php';
        $this->slim =& new App($container);
        $this->logger['kernel'] = new Logger(Logger::CHANNEL_KERNEL);
        $this->logger['slim'] = new Logger(Logger::CHANNEL_SLIM);

        // ExHan
        if (static::env() == static::DEVELOPMENT) {
            $this->slim->add(new ExHanMiddleware());
        } else {
            $this->slim->add(new ServerErrorMiddleware());
        }

        $this->registerEvents();
        //require path('configs') . 'events.php';

        $this->emitter->emit('app.start');

        // Defines the middleware from here.
        // Just a workaround...
        $config = new SessionConfig([
            'name' => Config::get('kernel.session.name', 'session'),
            'autorefresh' => Config::get('kernel.session.autorefresh', false),
            'lifetime' => Config::get('kernel.session.lifetime', '1 hour')
        ]);
        $this->session = new Handler($config);
        $this->cookies = new CookieJar();

        $middlewares = require path('configs') . 'middlewares.php';
        foreach ($middlewares as $middleware) {
            $this->slim->add($middleware);
            $this->emitter->emit('app.middleware.load', [$middleware]);
        }

        require path('configs') . 'routes.php';
        $this->slim->run();
        $this->emitter->emit('app.finish');
    }

    /**
     * Get or set the system environment
     *
     * @param string $environment The system environment to be set
     * @return string
     */
    public static function env($environment = null)
    {
        if ($environment) {
            static::$env = $environment;
        }

        return static::$env;
    }

    /**
     * Singleton caller
     * ---
     * Alias of the `__construct` method.
     *
     * @return object
     */
    public static function instance()
    {
        if (!static::$instance instanceof Kernel) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
