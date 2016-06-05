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

use Mavis\Monolog\Processor\IntrospectionProcessor;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as BaseLogger;
use Psr\Log\LoggerInterface;
use Mavis\Traits\LoggerTrait;
use Mavis\Config;
use Mavis\Kernel;

/**
 * Logger class
 * ---
 * Logger wrapper to ease a few things when in production
 *
 * @version 0.1.0
 */
class Logger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * Some channels
     */
    const CHANNEL_SLIM = 'slim';
    const CHANNEL_KERNEL = 'kernel';
    const CHANNEL_APP = 'application';

    /**
     * Monolog\Logger levels
     */
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING  = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;

    /**
     * Class constructor
     *
     * @param string $channel Channel name to be used as the logger identifier
     */
    public function __construct($channel = Logger::CHANNEL_APP)
    {
        $monolog = new BaseLogger(mb_strtoupper($channel));
        $this->setLogger($monolog);

        $this->pushHandlers();
        $this->pushProcessors();
        $this->pushFormatters();
    }

    /**
     * Handles the available handlers and pushes them to the Monolog\Logger instance
     *
     * @return void
     */
    protected function pushHandlers()
    {
        foreach (Config::get('kernel.logs.handlers') as $handler => $options) {
            switch ($handler) {
                case 'RotatingFileHandler':
                    if (!$options['enabled']) {
                        continue;
                    }

                    $level = static::nameToLevel($options['level']);
                    $chmod = 0 . $options['chmod'];

                    /*
                    $class = 'Monolog\\Formatter\\' . $options['formatter'];
                    $formatter = new $class();
                    */

                    // Is this handler enabled for this environment?
                    if (array_exists(array_flip($options['environments']), Kernel::env())) {
                        $rotateHandler = new RotatingFileHandler(
                            path('logs') . $options['filename'] . '.log', $options['max'], $level, $chmod
                        );
                        $rotateHandler->setFilenameFormat('{filename}-{date}', $options['date_format']);

                        $this->getLogger()->pushHandler(
                            $rotateHandler
                        );
                    }

                    break;
                case 'BrowserConsoleHandler':
                    if (!$options['enabled']) {
                        continue;
                    }

                    // Is this handler enabled for this environment?
                    if (array_exists(array_flip($options['environments']), Kernel::env())) {
                        $this->getLogger()->pushHandler(new BrowserConsoleHandler());
                    }

                    break;
            }
        }
    }

    /**
     * Handles the available processors and pushes them to the Monolog\Logger instance
     *
     * @return void
     */
    protected function pushProcessors()
    {
        foreach (Config::get('kernel.logs.processors') as $processor) {
            if (!$processor || $processor == 'none') {
                continue;
            }

            $class = 'Monolog\\Processor\\' . $processor;

            $this->getLogger()->pushProcessor(new $class());
        }

        // This processor is a fix for the original Monolog\Processor\IntrospectionProcessor
        $this->getLogger()->pushProcessor(new IntrospectionProcessor());
    }

    /**
     * Handles the available formatters and pushes them to Monolog\Logger instance
     *
     * @return void
     */
    protected function pushFormatters()
    {
        // Your neat and flawless code goes here :-)
    }
}
