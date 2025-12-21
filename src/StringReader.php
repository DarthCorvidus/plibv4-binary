<?php
namespace plibv4\binary;
use RuntimeException;
class StringReader {
	private string $string;
	private int $endian;
	private int $pos;
	const SE = 1;
	const LE = 2;
	const BE = 3;
	function __construct(string $string, int $endian) {
		if(!in_array($endian, array(self::SE, self::LE, self::BE))) {
			throw new RuntimeException("invalid byte order, use StringReader::SE, StringReader::LE, StringReader::BE");
		}
		$this->string = $string;
		$this->endian = $endian;
		$this->pos = 0;
	}
	
	function getInt8(): int {
		$value = IntVal::int8()->getValue(substr($this->string, $this->pos, 1));
		$this->pos += 1;
	return $value;
	}
	
	function getInt16(): int {
		if($this->endian==self::SE) {
			$value = IntVal::int16SE()->getValue(substr($this->string, $this->pos, 2));
			$this->pos += 2;
		return $value;
		}
	throw new RuntimeException("getInt16 not compatible with byte order LE/BE");
	}

	function getInt32(): int {
		if($this->endian==self::SE) {
			$value = IntVal::int32SE()->getValue(substr($this->string, $this->pos, 4));
			$this->pos += 4;
		} else {
			throw new RuntimeException("getInt32 not compatible with byte order LE/BE");
		}
	return $value;
	}

	function getInt64(): int {
		if($this->endian==self::SE) {
			$value = IntVal::int64SE()->getValue(substr($this->string, $this->pos, 8));
			$this->pos += 8;
		} else {
			throw new RuntimeException("getInt64 not compatible with byte order LE/BE");
		}
	return $value;
	}

	function getUInt8(): int {
		$value = IntVal::uint8()->getValue(substr($this->string, $this->pos, 1));
		$this->pos += 1;
	return $value;
	}
	
	function getUInt16(): int {
		$value = 0;
		if($this->endian==self::SE) {
			$value = IntVal::uint16SE()->getValue(substr($this->string, $this->pos, 2));
		}
		if($this->endian==self::LE) {
			$value = IntVal::uint16LE()->getValue(substr($this->string, $this->pos, 2));
		}
		if($this->endian==self::BE) {
			$value = IntVal::uint16BE()->getValue(substr($this->string, $this->pos, 2));
		}
		$this->pos += 2;
	return $value;
	}

	function getUInt32(): int {
		$value = 0;
		if($this->endian==self::SE) {
			$value = IntVal::uint32SE()->getValue(substr($this->string, $this->pos, 4));
		}
		if($this->endian==self::LE) {
			$value = IntVal::uint32LE()->getValue(substr($this->string, $this->pos, 4));
		}
		if($this->endian==self::BE) {
			$value = IntVal::uint32BE()->getValue(substr($this->string, $this->pos, 4));
		}
		$this->pos += 4;
	return $value;
	}

	function getUInt64(): int {
		$value = 0;
		if($this->endian==self::SE) {
			$value = IntVal::uint64SE()->getValue(substr($this->string, $this->pos, 8));
		}
		if($this->endian==self::LE) {
			$value = IntVal::uint64LE()->getValue(substr($this->string, $this->pos, 8));
		}
		if($this->endian==self::BE) {
			$value = IntVal::uint64BE()->getValue(substr($this->string, $this->pos, 8));
		}
		$this->pos += 8;
	return $value;
	}
	
	function getStringC(int $fixedLength = 0): string {
		$return = "";
		if($fixedLength!==0) {
			$return = trim(substr($this->string, $this->pos, $fixedLength));
			$len = strlen($return);
			if($len>$fixedLength-1) {
				throw new RuntimeException("payload size ".$len.", ".($fixedLength-1)." allowed.");
			}
			$this->pos += $fixedLength;
		return trim($return);
		}
		
		while($this->string[$this->pos]!==chr(0)) {
			$return .= $this->string[$this->pos];
			$this->pos++;
		}
		$this->pos++;
	return $return;
	}
	
	private function getIndexedString(int $width, int $fixedLength = 0): string {
		if(!in_array($width, array(8, 16, 32, 64))) {
			throw new RuntimeException("invalid index width, use 8, 16, 32, 64");
		}
		//$length = (int)call_user_func(array($this, "getUint".$width));
		$length = 0;
		switch ($width) {
			case 8:
				$length = $this->getUInt8();
			break;
			case 16:
				$length = $this->getUInt16();
			break;
			case 32:
				$length = $this->getUInt32();
			break;
			case 64:
				$length = $this->getUInt64();
			break;
		}
		$return = substr($this->string, $this->pos, $length);
		if($fixedLength==0) {
			$this->pos += $length;
		} else {
			$this->pos += $fixedLength;
		}
	return $return;
	}
	
	public function getString8(int $fixedLength = 0): string {
		return $this->getIndexedString(8, $fixedLength);
	}
	
	public function getString16(int $fixedLength = 0): string {
		return $this->getIndexedString(16, $fixedLength);
	}
	
	public function getString32(int $fixedLength = 0): string {
		return $this->getIndexedString(32, $fixedLength);
	}
	
	public function getString64(int $fixedLength = 0): string {
		return $this->getIndexedString(64, $fixedLength);
	}
}
