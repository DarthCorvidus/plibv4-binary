<?php
namespace plibv4\binary;
class Unpack {
	const BE = Pack::BE;
	const LE = Pack::LE;
	static function uInt8(string $string): int {
		return ord($string[0]);
	}
	
	static function sInt8(string $string): int {
		$int = self::uInt8($string);
	return ($int & 0x80) ? $int - 0x100 : $int;
	}
	
	static function uInt16(int $byteOrder, string $string): int {
		return self::unpackGenericInt(2, $byteOrder, $string);
	}

	static function sInt16(int $byteOrder, string $string): int {
		$int = self::unpackGenericInt(2, $byteOrder, $string);
	return ($int & 0x8000) ? $int - 0x10000 : $int;
	}
	
	static function uInt32(int $byteOrder, string $string): int {
		return self::unpackGenericInt(4, $byteOrder, $string);
	}

	static function sInt32(int $byteOrder, string $string): int {
		$int = self::unpackGenericInt(4, $byteOrder, $string);
	return ($int & 0x80000000) ? $int - 0x100000000 : $int;
	}
	
	static function sInt64(int $byteOrder, string $string): int {
		$int = self::unpackGenericInt(8, $byteOrder, $string);
	return $int;
	}
	
	private static function unpackGenericInt(int $width, int $byteOrder, string $string): int {
		$result = 0;
		if($byteOrder == self::LE) {
			for($i=0;$i<$width;$i++) {
				$result += ord($string[$i]) << $i*8;
			}
		return $result;
		}
		if($byteOrder == self::BE) {
			$string = strrev($string);
			for($i=0;$i<$width;$i++) {
				$result += ord($string[$i]) << $i*8;
			}
		return $result;
		}
	throw new \InvalidArgumentException("\$byteOrder needs to be ".self::class."::LE or ".self::class."::BE");
	}
}