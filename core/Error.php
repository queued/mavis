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

use Mavis\Version;

/**
 * Simple error class
 *
 * @version 0.1.0
 */
class Error
{
    /**
     * Halts the executing of the script and displays the error.
     *
     * @param string $title   Error title
     * @param string $message Error message
     * @param array  $trace   Exception stack trace
     */
    public static function halt($title, $message = '', $trace = [])
    {
        @ob_end_clean();

        $body = [];

        $body[] = "<html><head><title>{$title}</title></head>";
        $body[] = "<body style=\"margin:0;padding:0;\">";
        $body[] = "<blockquote style=\"font-family:'Helvetica Neue', Arial, Helvetica,
                    sans-serif;background:#fbe3e4;color:#8a1f11;padding:0.8em;margin:0;
                    border-bottom:1px solid #fbc2c4;\">";

        if (!$title !== null) {
            $body[] = "   <h1 style=\"font-weight:lighter;font-size:32px;border-bottom:1px solid #ce9c9c;
                        font-family:Arial,'Helvetica Neue',Verdana,sans-serif;line-height:50px;
                        margin:-10px 0 3px;color:#444\">{$title}</h1>";
        }

        $body[] = "<div style=\"color:#444;margin-top:12px;\"><em><span style=\"color:#970f0f;width:100%;
                    font-size:14px;\">{$message}</span></em></div>";

        if (count($trace)) {
            $body[] = "  <div style=\"margin-top:10px;\">";
            $body[] = "    <strong>Stack trace</strong>";
            $body[] = "    <ul style=\"margin-top:0;\">";
            foreach ($trace as $t) {
                $body[] = "      <li>Line #{$t['line']} in <code>{$t['file']}</code></li>";
            }
            $body[] = "    </ul>";
            $body[] = "  </div>";
        }

        $body[] = "  <div style=\"text-align:right;width:100%;display:inline-block;margin:8px 0 -5px;
            font-size:13px;\"><small>Powered by <strong>Mavis</strong> <em>" . Version::current() . "
            </em></small><div>";
        $body[] = "</blockquote>";
        $body[] = "</body></html>";

        echo implode(PHP_EOL, $body);
        exit(1);
    }
}
