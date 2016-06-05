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

namespace Mavis\Interfaces;

/**
 * Controller interface
 * ---
 * Provides some guidelines to inherited controllers when bootstraping the object.
 *
 * @author All Unser Miranda <miranda@codesans.com>
 * @version 0.1.0
 */
interface ControllerInterface
{
    /**
     * Set the template file to this controller
     *
     * @param string $name Template (file) name
     * @return void
     */
    public function setTemplate($name);

    /**
     * Get the template name of this controller
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Get the data to be evaluated when parsing the template
     *
     * @return array
     */
    public function getData();

    /**
     * It will be triggered when the controller is being called.
     *
     * NOTE: MUST return the response body.
     *
     * @param object $request Slim Request instance
     * @param object $response Slim Response instance
     * @param array $args URL parameters as an associative array
     * @return array
     */
    public function __invoke($request, $response, $args);
}
