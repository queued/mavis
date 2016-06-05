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

use \ArrayAccess;
use Mavis\Traits\ControllerTrait;
use Mavis\Interfaces\ControllerInterface;

/**
 * Controller class
 * ---
 * The base Controller to all existing controllers.
 *
 * @author All Unser Miranda <miranda@codesans.com>
 * @version 0.1.0
 */
abstract class Controller implements ArrayAccess, ControllerInterface
{
    use ControllerTrait;

    /**
     * Custom data
     *
     * @var array
     */
    protected $data = [];

    /**
     * SimpleAcl\Acl instance holder
     *
     * @var object
     */
    protected $acl = null;

    /**
     * Slim\App instance holder
     *
     * @var object
     */
    protected $slim = null;

    /**
     * Slim DI containcer (Pimple instance)
     *
     * @var object
     */
    protected $container = null;

    /**
     * Mavis\Middlewares\Session\Handler instance holder
     *
     * @var object
     */
    protected $session = null;

    /**
     * Slim\HttpCache\CacheProvider instance holder
     *
     * @var object
     */
    protected $httpcache = null;

    /**
     * Slim\Flash\Messages instance holder
     *
     * @var object
     */
    protected $flash = null;

    /**
     * Mavis\Output\View instance holder
     *
     * @var object
     */
    protected $view = null;

    /**
     * Mavis\Logger instance holder
     *
     * @var object
     */
    protected $logger = null;

    /**
     * Slim\Csrf\Guard instance holder
     *
     * @var object
     */
    protected $csrf = null;

    /**
     * Mavis\Middlewares\Cookies\Jar instance holder
     *
     * @var object
     */
    protected $cookie = null;

    /**
     * Template name
     *
     * @var string
     */
    protected $template = null;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->slim = Kernel::instance()->getSlim();
        $this->container = $this->slim->getContainer();

        $this->logger = $this->container->get('logger');
        $this->httpcache = $this->container->get('httpcache');
        $this->session = $this->container->get('session');
        $this->cookie = $this->container->get('cookie');
        $this->flash = $this->container->get('flash');
        $this->csrf = $this->container->get('csrf');
        $this->view = $this->container->get('view');
        $this->acl = $this->container->get('acl');

        /*
        $class = get_called_class();
        $class = str_replace(['Mavis\\App\\Controllers\\', 'Controller'], '', $class);
        $view = ($this->template) ? $this->template : strtolower($class);
        $this->view->setTemplate($view);
        */
    }

    /**
     * Get the value for the provided $key if it exists. Otherwise it will return null
     *
     * @param string $key Data identifier
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * Set the $key on the data stack with the provided $value
     *
     * @param string $key Data identifier
     * @param mixed $value Value for the provided $key
     * @return void
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Verify if the provided $key exists on the data stack
     *
     * @param string $key Data identifier
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset the provided key from the data stack
     *
     * @param string $key Data identifier
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Get the value for the provided $key if it exists. Otherwise it will return null
     *
     * @param string $key Data identifier
     * @return mixed
     */
    public function offsetGet($key)
    {
        return ($this->offsetExists($key)) ? array_get($this->data, $key) : null;
    }

    /**
     * Set the $key on the data stack with the provided $value
     *
     * @param string $key Data identifier
     * @param mixed $value Value for the provided $key
     * @return void
     */
    public function offsetSet($key, $value)
    {
        array_set($this->data, $key, $value);
    }

    /**
     * Verify if the provided $key exists on the data stack
     *
     * @param string $key Data identifier
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_exists($this->data, $key);
    }

    /**
     * Unset the provided key from the data stack
     *
     * @param string $key Data identifier
     * @return void
     */
    public function offsetUnset($key)
    {
        if ($this->offsetExists($key)) {
            array_remove($this->data, $key);
        }
    }

    /**
     * {@inheritDoc}
     */
    abstract public function __invoke($request, $response, $args);
}
