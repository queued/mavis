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

/**
 * ExceptionHandler class
 *
 * @version 0.1.0
 */
class Handler
{
    const E_UNKNOWN_ERROR = 0x10; // 16

    /**
     * Full path for the thrown exception file
     *
     * @var string
     */
    protected $fullpath = null;

    protected $type = null;
    protected $file = null;
    protected $path = null;
    protected $class = null;
    protected $function = null;
    protected $message = null;
    protected $line = 0;
    protected $code = 0;

    /**
     * Error codes
     *
     * @var array
     */
    protected $types = [
        E_ERROR             => 'Fatal Error',
        E_PARSE             => 'Parse Error',
        E_COMPILE_ERROR     => 'Compile Error',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_STRICT            => 'Strict Mode Error',
        E_NOTICE            => 'Notice',
        E_WARNING           => 'Warning',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED        => 'Deprecated', /* PHP 5.3 */
        E_USER_NOTICE       => 'Notice',
        E_USER_WARNING      => 'Warning',
        E_USER_ERROR        => 'Error',
        E_USER_DEPRECATED   => 'Deprecated', /* PHP 5.3 */
        Handler::E_UNKNOWN_ERROR   => 'Unknown Error'
    ];

    /**
     * Class constructor
     */
    public function __construct($exception)
    {
        $this->exception = $exception;

        $this->type = (array_key_exists($this->exception->getCode(), $this->types))
            ? $this->types[$this->exception->getCode()]
            : $this->types[Handler::E_UNKNOWN_ERROR];

        //$this->class = $this->exception->getClass();
        //$this->function = $this->exception->getFunction();
        $this->line = $this->exception->getLine();
        $this->code = $this->exception->getCode();
        $this->message = $this->exception->getMessage();

        $this->fullpath = str_replace(['\\', '/'], DS, $this->exception->getFile());
        $this->path = dirname($this->fullpath);
        $parts = explode(DS, $this->fullpath);
        $this->file = array_pop($parts);
    }

    /**
     * Retrieves an associative array with the exception data
     *
     * @return array
     */
    public function getExceptionData()
    {
        return [
            'type' => $this->type,
            'file' => $this->file,
            'path' => $this->fullpath,
            //'class' => $this->class,
            //'function' => $this->function,
            'line' => $this->line,
            'code' => $this->code,
            'message' => $this->message,
            'lines' => implode('', Handler::getPhpCode($this->fullpath, $this->line))
        ];
    }

    /**
     * Converts errors to ErrorExceptions.
     *
     * @author  Sergey Romanenko
     * @param   integer $code     The error code
     * @param   string  $message  The error message
     * @param   string  $file     The filename where the error occurred
     * @param   integer $line     The line number where the error occurred
     * @throws  \ErrorException
     * @return  boolean
     */
    public static function errorHandler($code, $message, $file, $line) {
        // If isset error_reporting and $code then throw new error exception
        if ((error_reporting() & $code) !== 0) {
            throw new \ErrorException($message, $code, 0, $file, $line);
        }

        // Don't execute PHP internal error handler
        return true;
    }

    /**
     * Convert errors not caught by the errorHandler to ErrorExceptions.
     *
     * @author Sergey Romanenko
     * @return void
     */
    public static function fatalErrorHandler() {
        // Get the last error
        $error = error_get_last();

        // If isset error then throw new error exception
        if (isset($error) && ($error['type'] === E_ERROR)) {
            new Handler(new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
        }
    }

    /**
     * Returns an array of lines from a file.
     *
     * @author  Sergey Romanenko
     * @param   string  $file
     * @param   integer $line
     * @param   integer $padding Number of padding lines
     * @return  array
     */
    protected static function getPhpCode($file, $line, $padding = 5)
    {
        // Is file readable?
        if (!is_readable($file)) {
            return false;
        }

        // Init vars
        $lines = [];
        $current_line = 0;

        // Open file
        $handle = fopen($file, 'r');

        // Read file
        while (!feof($handle)) {
            $current_line++;
            $temp = fgets($handle);

            if ($current_line > $line + $padding) {
                // Exit loop after we have found what we were looking for
                break;
            }

            $highlighted = ($current_line === $line);

            if ($current_line >= ($line - $padding) && $current_line <= ($line + $padding)) {
                $lines[] = $temp;
            }
        }

        // Closes the opened file stream
        fclose($handle);
        //dde($lines);

        // Return lines
        return $lines;
    }
}
