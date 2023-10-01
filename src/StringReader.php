<?php
namespace plibv4\Binary;
class StringReader {
	private $string;
	private $endian;
	private $pos;
	const SE = 1;
	const LE = 2;
	const BE = 3;
	function __construct(string $string, int $endian) {
		if(!in_array($endian, array(self::SE, self::LE, self::BE))) {
			throw new \RuntimeException("invalid byte order, use StringReader::SE, StringReader::LE, StringReader::BE");
		}
		$this->string = $string;
		$this->endian = $endian;
		$this->pos = 0;
	}
	
	function getInt8() {
		$value = \IntVal::int8()->getValue(substr($this->string, $this->pos, 1));
		$this->pos += 1;
	return $value;
	}
	
	function getInt16() {
		if($this->endian==self::SE) {
			$value = \IntVal::int16SE()->getValue(substr($this->string, $this->pos, 2));
			$this->pos += 2;
		} else {
			throw new \RuntimeException("getInt16 not compatible with byte order LE/BE");
		}
	return $value;
	}

	function getInt32() {
		if($this->endian==self::SE) {
			$value = \IntVal::int32SE()->getValue(substr($this->string, $this->pos, 4));
			$this->pos += 4;
		} else {
			throw new \RuntimeException("getInt16 not compatible with byte order LE/BE");
		}
	return $value;
	}

	function getInt64() {
		if($this->endian==self::SE) {
			$value = \IntVal::int64SE()->getValue(substr($this->string, $this->pos, 8));
			$this->pos += 8;
		} else {
			throw new \RuntimeException("getInt16 not compatible with byte order LE/BE");
		}
	return $value;
	}

	function getUInt8() {
		$value = \IntVal::uint8()->getValue(substr($this->string, $this->pos, 1));
		$this->pos += 1;
	return $value;
	}
	
	function getUInt16(): int {
		if($this->endian==self::SE) {
			$value = \IntVal::uint16SE()->getValue(substr($this->string, $this->pos, 2));
		}
		if($this->endian==self::LE) {
			$value = \IntVal::uint16LE()->getValue(substr($this->string, $this->pos, 2));
		}
		if($this->endian==self::BE) {
			$value = \IntVal::uint16BE()->getValue(substr($this->string, $this->pos, 2));
		}
		$this->pos += 2;
	return $value;
	}

	function getUInt32() {
		if($this->endian==self::SE) {
			$value = \IntVal::uint32SE()->getValue(substr($this->string, $this->pos, 4));
		}
		if($this->endian==self::LE) {
			$value = \IntVal::uint32LE()->getValue(substr($this->string, $this->pos, 4));
		}
		if($this->endian==self::BE) {
			$value = \IntVal::uint32BE()->getValue(substr($this->string, $this->pos, 4));
		}
		$this->pos += 4;
	return $value;
	}

	function getUInt64() {
		if($this->endian==self::SE) {
			$value = \IntVal::uint64SE()->getValue(substr($this->string, $this->pos, 8));
		}
		if($this->endian==self::LE) {
			$value = \IntVal::uint64LE()->getValue(substr($this->string, $this->pos, 8));
		}
		if($this->endian==self::BE) {
			$value = \IntVal::uint64BE()->getValue(substr($this->string, $this->pos, 8));
		}
		$this->pos += 8;
	return $value;
	}

}
