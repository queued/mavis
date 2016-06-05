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

use Mavis\Controller;

class ContactController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function __invoke($request, $response, $args)
    {
        $this->data = [
            'title' => 'Contact Us',
            'flash' => $this->flash->getMessages()
        ];

        if ($request->isGet()) {
            $this->index($request);
        } elseif ($request->isPost()) {
            $this->handlePost($request, $response);
        }

        $this->view->setTemplate('contact');
        $this->view->render($this->data);

        $this->logger->info('Contact route successful');
        return $response;
    }

    public function index($request)
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $this->data += [
            // Please read the docs about the code below
            // https://mavisphp.com/docs/controllers/ContactController.md
            'csrf_name_key' => $nameKey,
            'csrf_value_key' => $valueKey,
            'csrf_name' => $request->getAttribute($nameKey),
            'csrf_value' => $request->getAttribute($valueKey)
        ];
    }

    public function handlePost($request, $response)
    {
        if ($request->getAttribute('name') && $request->getAttribute('email') && $request->getAttribute('message')) {
            $this->flash->addMessage('status', ['code' => 'success', 'msg' => 'Your message has been sent!']);
        } else {
            $this->flash->addMessage('status', ['code' => 'error', 'msg' => 'Please fill all fields.']);
        }

        return $response->withStatus(302)->withHeader('Location', '/contact');
    }
}
