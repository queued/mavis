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

namespace Mavis\Traits;

use Psr\Log\LoggerInterface;
use Mavis\Logger;
use Mavis\Kernel;
use Mavis\Config;

/**
 * Logger trait
 *
 * @version 0.1.0
 */
trait LoggerTrait
{
    /**
     * Monolog\Logger instance
     *
     * @var object
     */
    protected $logger = null;

    /**
     * Converts the given name into an existing level (string -> integer)
     *
     * @param string $level Level name to be converted
     * @return int
     */
    public static function nameToLevel($level)
    {
        if (is_string($level)) {
            $level = mb_strtoupper($level);

            if (defined(__CLASS__ . '::' . $level)) {
                return constant(__CLASS__ . '::' . $level);
            }
        }

        return $level;
    }

    /**
     * Adds a log record at an arbitrary level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param mixed $level   The log level
     * @param string $message The log message
     * @param array $context The log context
     * @return bool
     */
    public function log($level = Logger::DEBUG, $message, array $context = [])
    {
        $level = static::nameToLevel($level);

        if (Config::get('kernel.environment.' . Kernel::env() . '.logging') == false) {
            // Logging is disabled for this environment
            return false;
        }

        return $this->getLogger()->addRecord($level, $message, $context);
    }

    /**
     * Adds a log record at the DEBUG level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param string $message The log message
     * @param array $context The log context
     * @return bool
     */
    public function debug($message, array $context = [])
    {
        return $this->log(static::DEBUG, $message, $context);
    }

    /**
     * Adds a log record at the INFO level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param string $message The log message
     * @param array $context The log context
     * @return bool
     */
    public function info($message, array $context = [])
    {
        return $this->log(static::INFO, $message, $context);
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param string $message The log message
     * @param array $context The log context
     * @return bool
     */
    public function notice($message, array $context = [])
    {
        return $this->log(static::NOTICE, $message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param string $message The log message
     * @param array $context The log context
     * @return bool
     */
    public function warning($message, array $context = [])
    {
        return $this->log(static::WARNING, $message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param string $message The log message
     * @param array $context The log context
     * @return bool
     */
    public function error($message, array $context = [])
    {
        return $this->log(static::ERROR, $message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param string $message The log message
     * @param array $context The log context
     * @return bool
     */
    public function critical($message, array $context = [])
    {
        return $this->log(static::CRITICAL, $message, $context);
    }
    /**
     * Adds a log record at the ALERT level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param string $message The log message
     * @param array $context The log context
     * @return bool
     */
    public function alert($message, array $context = [])
    {
        return $this->log(static::ALERT, $message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param string $message The log message
     * @param array $context The log context
     * @return bool
     */
    public function emergency($message, array $context = [])
    {
        return $this->log(static::EMERGENCY, $message, $context);
    }

    /**
     * Get the Monolog\Logger instance
     *
     * @return object
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Set the logger object
     *
     * @param Psr\Log\LoggerInterface $logger A valid Psr\Log\LoggerInterface instance
     */
    public function setLogger(LoggerInterface $logger)
    {
        if (!$this->getLogger()) {
            $this->logger = $logger;
        }
    }
}
