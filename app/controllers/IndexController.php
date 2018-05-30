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

namespace Mavis\App\Controllers;

use Mavis\Cookie;
use Mavis\Controller;

class IndexController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function __invoke($request, $response, $args)
    {
        if (!$this->session->exists('mavis')) {
            $this->session->set('mavis', 'This text has been stored inside the session');
        }

        if (!$this->cookie->exists('mavis')) {
            $this->cookie->set(new Cookie(['name' => 'mavis', 'value' => 'my awesome app!', 'lifetime' => '5 minutes']));
        }

        $data = [
            'cookie_data' => $this->cookie->get('mavis', '[ none ]'),
            'session_data' => $this->session->get('mavis', '[ none ]'),
            'title' => 'Welcome',
            'link' => 'https://la2eden.com',
            'args' => $args
        ];

        $this->view->setTemplate('index');
        $this->view->with($data)->render();

        $this->logger->info('Index route successful');
        return $response;
    }
}
