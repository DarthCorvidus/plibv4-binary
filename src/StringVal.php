<?php
/**
 * Imports and exports strings from and to binary files. Please note that the
 * length of a string includes a terminating null byte, so if StringVal is
 * called with a length of 11, the string is allowed to be 10 characers long.
 * Also note that StringVal does not care about multibyte character sets!
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */
namespace plibv4\binary;
use InvalidArgumentException;
/**
 * @implements BinVal<string>
 */
class StringVal implements BinVal {
	private int $length;
	private string $pad;
	function __construct(int $length) {
		$this->length = $length;
		$this->pad = "\0";
	}
	
	/**
	 * Length of the string including terminating Null-byte.
	 * @return int
	 */
	#[\Override]
	public function getLength(): int {
		return $this->length;
	}

	/**
	 * Return string from input (without null byte)
	 * @param string $string
	 * @return string
	 * @throws InvalidArgumentException
	 */
	#[\Override]
	public function getValue(string $string): string {
		$str = substr($string, 0, $this->length);
		if(strlen($str)<$this->length) {
			throw new InvalidArgumentException("input too short, ".$this->length." expected.");
		}
		if($str[$this->length-1]!=$this->pad) {
			throw new InvalidArgumentException("input not null-terminated.");
		}

		$return = "";
		for($i = 0; $i<strlen($str);$i++) {
			if($str[$i]==$this->pad) {
				return $return;
			}
			$return .= $str[$i];
		}
	return $return;
	}

	/**
	 * Takes string and turns it into a null-terminated string, padding with
	 * null bytes if necessary.
	 * @param string $value
	 * @return string
	 * @throws InvalidArgumentException
	 */
	#[\Override]
	public function putValue($value): string {
		if(strlen($value)>$this->length) {
			throw new InvalidArgumentException("String too long, ".($this->length-1)." characters allowed.");
		}
		return str_pad($value, $this->length, $this->pad, STR_PAD_RIGHT);
	}

}
