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

/**
 * Cipher helper class
 *
 * @version 0.1.0
 */
class Cipher
{
    /**
     * Crypting algorithm to be used
     *
     * @var integer
     */
    protected $algorithm = MCRYPT_RIJNDAEL_128;

    /**
     * Crypting mode
     *
     * @var integer
     */
    protected $mode = MCRYPT_MODE_ECB;

    /**
     * Hash algorithm to be used
     *
     * @var string
     */
    protected $hasher = 'salsa20';

    /**
     * Salt string to be used when using the Cipher
     *
     * @var string
     */
    protected $salt = null;

    /**
     * Default hashing iterations
     *
     * @var integer
     */
    protected $iterations = 10;

    /**
     * Class construtor
     *
     */
    public function __construct($algorithm, $mode, $hasher, $salt, $iterations = 10)
    {
        $this->iterations = $iterations;
        $this->algorithm = $algorithm;
        $this->hasher = $hasher;
        $this->salt = $salt;
        $this->mode = $mode;
    }

    /**
     * Allow verification chaining outside this class
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
	 * Encrypts the given string
	 *
	 * @param string $string Some string to encrypt.
	 * @return string
	 */
    public function encrypt($string)
    {
        $key = $this->hash($this->salt);

        $iv = mcrypt_create_iv(mcrypt_get_iv_size($this->algorithm, $this->mode), MCRYPT_DEV_URANDOM);
		$encrypted = trim(base64_encode(mcrypt_encrypt($this->algorithm, $key, $string, $this->mode, $iv)));

		return $encrypted;
    }

    /**
     * Decrypts the given string
     *
     * @param string$encrypted Encrypted string to be decrypted
     * @return string
     */
    public function decrypt($encrypted)
    {
        $key = $this->hash($this->salt);

        $iv = mcrypt_create_iv(mcrypt_get_iv_size($this->algorithm, $this->mode), MCRYPT_DEV_URANDOM);
		$decrypted = trim(mcrypt_decrypt($this->algorithm, $key, base64_decode($encrypted), $this->mode, $iv));

		return $decrypted;
    }

    /**
	 * PBKDF2 Implementation (described in RFC 2898)
	 *
	 * @author	Andrew Johnson
	 * @param 	string 	$string 	Some string to hash
	 * @param 	int 	$len 	Derived key length (only 16, 24 or 32 are supported)
	 * @param 	string 	$algo 	hash algorithm
	 * @return 	string 			Hashed string
	 */
	public function hash($string, $length = 32) {
		$hl = strlen(hash($this->hasher, null, true)); # Hash length
		$kb = ceil($length / $hl); # Key blocks to compute
		$key = ''; # Derived key

		# Create key
		for ($block = 1; $block <= $kb; $block++) {
			# Initial hash for this block
			$ib = $b = hash_hmac($this->hasher, $this->salt . pack('N', $block), $string, true);

			# Perform block iterations
			for ($i = 1; $i < $this->iterations; $i++) {
				# XOR each iterate
				$ib ^= ($b = hash_hmac($this->hasher, $b, $string, true));
			}

			$key .= $ib; # Append iterated block
		}

		# Return derived key of correct length
		return substr($key, 0, $length);
	}

    /**
     * Check if the given $string matches with the existing $hash
     *
     * @param string $string Unhashed string
     * @param string $hash Existing hash to check against
     * @param integer $length Key length
     * @return boolean
     */
    public function match($string, $hash, $length = 32)
    {
        return ($this->hash($string, $length) == $hash) ?: false;
    }
}
