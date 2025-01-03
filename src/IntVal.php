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
 * @implements BinVal<int>
 */
class IntVal implements BinVal {
	private $packchar;
	private $size;
	private function __construct(string $packchar, int $size) {
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
	
	/**
	 * 
	 * @param string $string
	 * @return int
	 * @throws RuntimeException
	 */
	function getValue(string $string): int {
		if($string==="") {
			throw new RuntimeException("binary value is empty.");
		}
		$unpack = unpack($this->getPackChar(), $string);
	return (int)$unpack[1];
	}
	
	function getLength(): int {
		return $this->size;
	}
	/**
	 * 
	 * @param mixed $value
	 * @return string
	 * @throws RuntimeException
	 */
	function putValue($value): string {
		/**
		 * Yes, but the compiler does not know about Docblock.
		 * @psalm-suppress DocblockTypeContradiction
		 */
		if($value==="") {
			throw new RuntimeException("parameter \$value is empty");
		}
		/** @psalm-suppress DocblockTypeContradiction */
		if(!is_int($value)) {
			throw new RuntimeException("parameter \$value is not an integer, but ".gettype($value));
		}
		return pack($this->getPackChar(), $value);
	}
}
