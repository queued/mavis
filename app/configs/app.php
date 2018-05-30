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

return [
    'site' => [
        'name' => 'Mavis',
        'url' => 'localhost',
        'https' => false,
        'keywords' => '',
        'description' => '',
        'language' => 'pt_BR',
    ],
    'database' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'persistent' => true // improves WEBSITE performance, but decreases DATABASE performance
    ],
    'kernel' => [
        'timezone' => 'America/Sao_Paulo',
        'session' => [
            'name' => 'session',
            'autorefresh' => false,
            'lifetime' => '1 hour'
        ],
        'cipher' => [
            'algorithm' => MCRYPT_RIJNDAEL_256,
            'mode' => MCRYPT_MODE_ECB,
            'hash_algo' => 'whirlpool',
            'salt' => 'sk4mapp@#', // Salt Key For Mavis App @#
            'iterations' => 10
        ],
        'environment' => [
            'development' => [
                'compression' => false,
                'debug' => true,
                'logging' => true
            ],
            'production' => [
                'compression' => true,
                'debug' => false,
                'logging' => true
            ]
        ],
        'logs' => [
            'handlers' => [
                'RotatingFileHandler' => [
                    'enabled' => true,
                    'filename' => 'mavis',
                    'date_format' => 'd-m-Y',
                    'chmod' => 644,
                    'level' => 'notice',
                    'max' => 30,
                    'environments' => ['production']
                ],
                'BrowserConsoleHandler' => [
                    'enabled' => true,
                    'environments' => ['development']
                ]
            ],
            'processors' => [
                'WebProcessor',
                'MemoryUsageProcessor',
                'UidProcessor'
            ]
        ]
    ]
];
