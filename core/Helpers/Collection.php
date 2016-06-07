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

namespace Mavis\Helpers;

use \Countable;
use \ArrayAccess;
use \JsonSerializable;
use \IteratorAggregate;

/**
 * Collection class
 * ---
 * Just an array collection to be used as memory storage.
 *
 * @author All Unser Miranda <miranda@codesans.com>
 * @version 0.1.0
 */
class Collection implements Countable, JsonSerializable, ArrayAccess, IteratorAggregate
{
    /**
     * Array stack (data holder)
     *
     * @var array
     */
    private $stack = [];

    /**
     * Class constructor
     * ---
     *
     * Pushes the given stack to the existing object stack
     *
     * @param  array  $stack Array to be pushed
     * @param  bool $override Can override existing entries?
     * @return void
     */
    public function __construct(array $stack = [])
    {
        if ($stack) {
            foreach ($stack as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    /**
	 * Create a new Collection instance
	 *
	 * @param  mixed $stack Stack of items to be specified in the new Collection
	 * @return object
	 */
	public static function make($stack = null)
	{
		return new static($stack);
	}

    /**
     * Returns the existing matched entry in the stack
     *
     * @param  string $key Key to search for
     * @param  mixed $default Default value to be returned in case it doesn't find anything
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($key == '*') {
            return $this->all();
        }

        if ($this->exists($key)) {
            return $this->offsetGet($key);
        }

        return $default;
    }

    /**
     * Sets a new entry
     *
     * @param string $key Key identifier
	 * @param mixed $value Value for the given $key
     * @param bool
     */
    public function set($key, $value, $override = true)
    {
		if ($this->exists($key) && !$override) {
            return false;
        }

        $this->offsetSet($key, $value);
    }

    /**
     * Whether an entry exists or not
     *
     * @param  string $key Key to search for
     * @return bool
     */
    public function exists($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Removes an entry from the stack by the given $key
     *
     * @param  string $key Key to search for
     * @return void
     */
    public function remove($key)
    {
        if ($this->offsetExists($key)) {
            $this->offsetUnset($key);
        }
    }

    /**
     * Checks if the given value exists in the array
     *
     * @param mixed $value The value to search
     * @return bool
     */
    public function contains($value)
    {
        return $this->indexOf($value) !== false;
    }

    /**
     * Returns the index of given element
     *
     * @param mixed $value The index for value
     * @return mixed
     */
    public function indexOf($value)
    {
        return array_search($value, $this->stack, true);
    }

    /**
	 * Get all of the items in the collection.
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->stack;
	}

    /**
	 * Diff the collection with the given items.
	 *
	 * @param  array  $items
	 * @return static
	 */
	public function diff(array $diff)
	{
		return new static(array_diff($this->stack, $diff));
	}

    /**
     * Applies the callback to the elements
     *
     * @param callable $callback
     */
    public function each(callable $callback)
    {
        array_map($callback, $this->stack);

        return $this;
    }

    /**
	 * Run a filter over each of the items.
	 *
	 * @param  callable $callback A valid callback to be used as filter
	 * @return object
	 */
	public function filter(callable $callback)
	{
		return new static(array_filter($this->stack, $callback));
	}

    /**
	 * Run a map over each of the items.
	 *
	 * @param  callable $callback A valid callback to be used as mapping function
	 * @return object
	 */
	public function map(callable $callback)
	{
		return new static(array_map($callback, $this->stack, array_keys($this->stack)));
	}

    /**
	 * Transform each item in the collection using a callback.
	 *
	 * @param  callable $callback A valid callback to be used as transforming function
	 * @return object
	 */
	public function transform(callable $callback)
    {
		$this->items = array_map($callback, $this->stack);

		return $this;
	}

    /**
	 * Merge the collection with the given items.
	 *
	 * @param  array $array1 ...
	 * @param  array $array2 ...
	 * @param  array $array3 ...
	 * @return object
	 */
	public function merge()
	{
		return new static(array_merge($this->stack, func_get_args()));
	}

    /**
	 * Flip the items in the collection.
	 *
	 * @return object
	 */
	public function flip()
	{
		return new static(array_flip($this->items));
	}

    /**
	 * Intersect the collection with the given items.
	 *
 	 * @param  array $haystack Haystack of items to be used as intersection comparisor
	 * @return object
	 */
	public function intersect($haystack)
	{
		return new static(array_intersect($this->stack, $haystack));
	}

    /**
     * Join all elements of an array into a string.
     *
     * @param string $separator Glue of each element
     * @return string Imploded array
     */
    public function join($separator = ',')
    {
        return implode($separator, $this->stack);
    }

    /**
	 * Push an item onto the beginning of the collection.
	 *
	 * @param  mixed $value Value to be prepended
	 * @return void
	 */
	public function prepend($value)
	{
		array_unshift($this->stack, $value);
	}



    /**
     * Remove the first element from an array and returns that element.
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->stack);
    }

    /**
     * Remove the last element from an array and returns that element.
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->stack);
    }

    /**
	 * Get one or more items randomly from the collection.
	 *
	 * @param  int $amount Amount of items to randomly get
	 * @return mixed
	 */
	public function random($amount = 1)
	{
		if ($this->isEmpty()) {
            return;
        }

		$keys = array_rand($this->stack, $amount);
		return is_array($keys) ? array_intersect_key($this->stack, array_flip($keys)) : $this->stack[$keys];
	}

    /**
	 * Reverse the stack order.
	 *
	 * @return object
	 */
	public function reverse()
	{
		return new static(array_reverse($this->stack));
	}

    /**
     * Count elements of the stack
     *
     * @return int
     */
    public function count()
    {
        return count($this->stack);
    }

    /**
	 * Get the keys of the collection items.
	 *
	 * @return object
	 */
	public function keys()
	{
		return new static(array_keys($this->stack));
	}

    /**
     * Shuffle collection
     *
     * @return object
     */
    public function shuffle()
    {
        shuffle($this->stack);

        return $this;
    }

    /**
	 * Sort through each item with a callback.
	 *
	 * @param  callable $callback
	 * @return object
	 */
	public function sort(callable $callback)
	{
		uasort($this->stack, $callback);

		return $this;
	}

    /**
	 * Return only unique items from the collection array.
	 *
	 * @return object
	 */
	public function unique()
	{
		return new static(array_unique($this->stack));
	}
	/**
	 * Reset the keys on the underlying array.
	 *
	 * @return object
	 */
	public function values()
	{
		return new static(array_values($this->stack));
	}

    /**
     * Remove all elements from the stack leaving it with length 0.
     *
     * @return object
     */
    public function clear()
    {
        $this->stack = [];

        return $this;
    }

    /**
	 * Determine if the collection is empty or not.
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->stack);
	}

    /**
     * Retrieve an external iterator
     *
     * @return object
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->stack);
    }

    /**
     * Specify the data to be serialized when using json_encode
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->all();
    }

    /**
	 * Determine if an item exists at an offset.
	 *
	 * @param  string $key Key to search for
     * @return bool
     */
	public function offsetExists($key)
	{
		return array_key_exists($key, $this->stack);
	}
	/**
	 * Get an item at a given offset.
	 *
	 * @param  string $key Key to search for
     * @param  mixed $default Default value to be returned in case it doesn't find anything
     * @return mixed
     */
	public function offsetGet($key)
	{
		return $this->stack[$key];
	}
	/**
	 * Set the item at a given offset.
	 *
	 * @param string $key Key identifier
	 * @param mixed $value Value for the given $key
     * @param bool
     */
	public function offsetSet($key, $value)
	{
		if (is_null($key)) {
			$this->stack[] = $value;
		} else {
			$this->stack[$key] = $value;
		}
	}
	/**
	 * Unset the item at a given offset.
	 *
	 * @param  string $key Key to search for
     * @return void
     */
	public function offsetUnset($key)
	{
		unset($this->stack[$key]);
	}

    /**
	 * Convert the collection to its string representation.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return json_encode($this->stack, JSON_PRETTY_PRINT);
	}
}
