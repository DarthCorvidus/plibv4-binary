<?php
namespace plibv4\binary;
/**
 * Low level eqivalent to pack. I don't use Pack as a direct frontend to pack,
 * but do the conversion myself in plain PHP. This will be slightly slower than
 * using pack itself, but it sure looks nicer than having a large hashmap with
 * all of pack's letters.
 */
class Pack {
	const SE = 0;
	/** @var int Six-Teen (least significant number first) */
	const LE = 1;
	/** @var int Twenty-Six (most significant number first) */
	const BE = 2; 
	/**
	 * Unsigned 8bit integer (0 to 255)
	 * @param int $int
	 * @return string
	 */
	static function uInt8(int $int): string {
		return chr($int);
	}
	/**
	 * Signed 8bit integer (-128 to +127)
	 * @param int $int
	 * @return string
	 */
	static function sInt8(int $int): string {
		return chr($int);
	}

	/**
	 * Unsigned 16bit integer (0 to 65535)
	 * @param int $byteOrder
	 * @param int $int
	 * @return string
	 */
	static function uInt16(int $byteOrder, int $int): string {
		return self::packGenericInt(2, $byteOrder, $int);
	}
	
	/**
	 * Signed 16bit integer (-32768 to 32767)
	 * @param int $byteOrder
	 * @param int $int
	 * @return string
	 */
	static function sInt16(int $byteOrder, int $int): string {
		return self::uInt16($byteOrder, $int);
	}
	
	/**
	 * Unsigned 32bit integer (0 to 4294967295)
	 * @param int $byteOrder
	 * @param int $int
	 * @return string
	 */
	static function uInt32(int $byteOrder, int $int): string {
		return self::packGenericInt(4, $byteOrder, $int);
	}

	/**
	 * Signed 32bit Integer (−2147483648 to 2147483647)
	 * @param int $byteOrder
	 * @param int $int
	 * @return string
	 */
	static function sInt32(int $byteOrder, int $int): string {
		return self::uInt32($byteOrder, $int);
	}

	/**
	 * Signed 64bit Integer (−9223372036854775808 to +9223372036854775807)
	 * Note that PHP stores any int value as a signed 64bit integer on a 64bit
	 * system. uInt64 therefore makes no sense, at least you're not able to load
	 * it unless loading it into a float (yuck).
	 * 
	 * @param int $byteOrder
	 * @param int $int
	 * @return string
	 */
	static function sInt64(int $byteOrder, int $int): string {
		return self::packGenericInt(8, $byteOrder, $int);
	}

	
	private static function packGenericInt(int $width, int $byteOrder, int $int ): string {
		$string = "";
		if($byteOrder === self::BE) {
			for($i=0;$i<$width;$i++) {
				$string = chr(($int >> $i*8) & 255).$string;
			}
		return $string;
		}
		if($byteOrder === self::LE) {
			for($i=0;$i<$width;$i++) {
				$string .= chr(($int >> $i*8) & 255);
			}
		return $string;
		}
	throw new \InvalidArgumentException("\$byteOrder needs to be ".self::class."::LE or ".self::class."::BE");
	}
}