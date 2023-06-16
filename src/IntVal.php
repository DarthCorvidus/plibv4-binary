<?php
/**
 * IntVal imports and exports integers using unpack() and pack. Use static
 * methods to intantiate class. Static methods are named as follows:
 * uint16LE:<br />
 * (u)nsigned<br />
 * int<br />
 * 16 = 16bit<br />
 * LE = Little Endian
 * 
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */
class IntVal implements BinVal {
	private $packchar;
	private $size;
	private function __construct($packchar, int $size) {
		$this->packchar = $packchar;
		$this->size = $size;
	}
	
	function getPackChar(): string {
		return $this->packchar;
	}

	static function int8(): IntVal {
		$return = new IntVal("c", 1);
	return $return;
	}
	
	static function uint8(): IntVal {
		$return = new IntVal("C", 1);
	return $return;
	}

	static function int16SE(): IntVal {
		$return = new IntVal("s", 2);
	return $return;
	}
	
	static function uint16SE(): IntVal {
		$return = new IntVal("S", 2);
	return $return;
	}

	static function uint16BE(): IntVal {
		$return = new IntVal("n", 2);
	return $return;
	}

	static function uint16LE(): IntVal {
		$return = new IntVal("v", 2);
	return $return;
	}

	static function int32SE(): IntVal {
		$return = new IntVal("l", 4);
	return $return;
	}
	
	static function uint32SE(): IntVal {
		$return = new IntVal("L", 4);
	return $return;
	}

	static function uint32BE(): IntVal {
		$return = new IntVal("N", 4);
	return $return;
	}

	static function uint32LE(): IntVal {
		$return = new IntVal("V", 4);
	return $return;
	}

	static function int64SE(): IntVal {
		$return = new IntVal("q", 8);
	return $return;
	}
	
	static function uint64SE(): IntVal {
		$return = new IntVal("Q", 8);
	return $return;
	}

	static function uint64BE(): IntVal {
		$return = new IntVal("J", 8);
	return $return;
	}

	static function uint64LE(): IntVal {
		$return = new IntVal("P", 8);
	return $return;
	}
	
	function getValue(string $value) {
		if($value==="") {
			throw new RuntimeException("binary value is empty.");
		}
		$unpack = unpack($this->getPackChar(), $value);
	return $unpack[1];
	}
	
	function getLength(): int {
		return $this->size;
	}
	
	function putValue($value): string {
		if($value==="") {
			throw new RuntimeException("binary value is empty.");
		}
		return pack($this->getPackChar(), $value);
	}
}
