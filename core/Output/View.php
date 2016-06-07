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

namespace Mavis\Output;

use Razr\Engine;
use Razr\Loader\FilesystemLoader;
use Mavis\Helpers\Benchmark;
use Mavis\Config;
use Mavis\Kernel;
use Slim\Http\Response;

/**
 * View class
 * ---
 * Handles the 'view' part of the application
 *
 * @version 0.1.0
 */
class View
{
	protected $parser = null;
	protected $hasBeenParsed = false;
	protected $parsedContent = '';
	protected $template = '';
	protected $data = [];

	/**
	 * Slim\Http\Response instance
	 *
	 * @var object
	 */
	protected $response;

	/**
	 * Class constructor
	 *
	 * @param string $response Slim\Http\Response instance to be used
	 */
	public function __construct(Response $response)
	{
		$this->response = $response;

		$this->parser = new Engine(
			new FilesystemLoader(path('views'), path('cache'))
		);
		$this->hasBeenParsed = false;
	}

    /**
     * Specify the template name
     *
     * @param string $template Template file to be rendered
     */
    public function setTemplate($template)
    {
		if (!$this->template) {
			$this->template = str_replace('.', DS, $template) . '.razr';
		}
    }

	/**
	 * Assign some data to our template
	 *
	 * @param array $data Key-pairs to be assigned
	 * @return object
	 */
	public function with(array $data)
	{
		$this->data += $data;

		return $this;
	}

	/**
	 * Parses the content of this view
	 *
	 * @param array $data Additional data to be parsed as variables
	 * @param bool $return Should the parsed view be returned as a string?
	 * @return mixed
	 */
	public function parse(array $data = [], $return = false)
	{
		// Some pre-defined variables accessible inside the template
		$this->data['appname'] = Config::get('site.name');
		$url = Config::get('site.url');
		$this->data['appurl'] = (Config::get('site.https')) ? 'https://' . $url : 'http://' . $url;
		$this->data['description'] += Config::get('site.description');
		$this->data['keywords'] += Config::get('site.keywords');
		$this->data['render_time'] = Benchmark::time('start');
		$this->data['memory_usage'] = Benchmark::memory('start');

		$this->parsedContent = $this->parser->render(
			$this->template, array_merge($data, $this->data)
		);

		$this->hasBeenParsed = true;

		if ($return) {
			return $this->getParsedView();
		}
	}

	/**
	 * Get the parsed view
	 *
	 * @return string
	 */
	public function getParsedView()
	{
		if (!$this->hasBeenParsed) {
			$this->parse();
		}

		return $this->parsedContent;
	}

	/**
	 * Renders the parsed content in the browser window
	 *
	 * @param  array $data Custom variables to be used when parsing the template
	 * @return void
	 */
	public function render(array $data = [])
	{
		$this->parse($data);

		$this->response->write($this->parsedContent);
	}

	/**
	 * Compress the given HTML
	 *
	 * @param string $html HTML to be compressed
	 * @return string
	 */
	public static function compress($html)
	{
		$html .= "\n";
		$out = '';
		$inside_pre = false;
		$bytecount  = 0;

		while ($line = static::getLine($html)) {
			$bytecount += strlen($line);
			if (!$inside_pre) {
				if (strpos($line, '<pre') === false) {
					// Since we're not inside a <pre> block, we can trim both ends of the line
					$line = trim($line);

					// And condense multiple spaces down to one
					$line = preg_replace('/\s\s+/', ' ', $line);
				} else {
					// Only trim the beginning since we just entered a <pre> block...
					$line = ltrim($line);
					$inside_pre = true;
					// If the <pre> ends on the same line, don't turn on $inside_pre...
					if ((strpos($line, '</pre') !== false) && (strripos($line, '</pre') >= strripos($line, '<pre'))) {
						$line = rtrim($line);
						$inside_pre = false;
					}
				}
			} else {
				if ((strpos($line, '</pre') !== false) && (strripos($line, '</pre') >= strripos($line, '<pre'))) {
					// Trim the end of the line now that we found the end of the <pre> block...
					$line = rtrim($line);
					$inside_pre = false;
				}
			}
			// Filter out any blank lines that aren't inside a <pre> block...
			if ($inside_pre || $line != '') {
				$out .= $line . '\n';
			}
		}

		$out = preg_replace('/(<!--.*?-->)/ms', '', $out);
		$out = str_replace('<!>', '', $out);

		// Remove the trailing \n
		return trim($out);
	}

	/**
	 * Get the next line from a string
	 *
	 * @param string $data String to extract
	 * @return string
	 */
	protected static function getLine(&$data)
	{
		if (strlen($data) > 0) {
			$pos = strpos($data, '\n');
			$return = substr($data, 0, $pos) . "\n";
			$data = substr($data, $pos + 1);

			return $return;
		}

		return false;
	}
}
