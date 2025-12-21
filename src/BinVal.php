<?php
/**
 * Interface for a single binary value, to be read or written from or to a file
 * or string.
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * 
 */
namespace plibv4\binary;
/**
 * @template T
 */
interface BinVal {
	/**
	 * This is supposed to return a binary string ready to be written to a
	 * binary file.
	 * @param T $value
	 */
	function putValue($value): string;
	/**
	 * This is supposed to convert a binary value into a PHP datatype, ie 'a'
	 * becomes 100 when read as int.
	 * 
	 * @param string $string
	 * @return T
	 */
	function getValue(string $string);
	/**
	 * Length of the binary data in bytes, ie a 32bit integer would be 4 bytes
	 * long.
	 */
	function getLength(): int;
}
