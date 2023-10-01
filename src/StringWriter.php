<?php
namespace plibv4\Binary;
class StringWriter {
	private $string;
	private $endian;
	const SE = 1;
	const LE = 2;
	const BE = 3;
	function __construct(int $endian) {
		if(!in_array($endian, array(self::SE, self::LE, self::BE))) {
			throw new \RuntimeException("invalid byte order, use StringWriter::SE, StringWriter::LE, StringWriter::BE");
		}
		$this->string = "";
		$this->endian = $endian;
	}
	
	function addInt8(int $value) {
		$this->string .= \IntVal::int8()->putValue($value);
	}

	function addUInt8(int $value) {
		$this->string .= \IntVal::uint8()->putValue($value);
	}

	function addInt16(int $value) {
		if($this->endian==self::SE) {
			$this->string .= \IntVal::int16SE()->putValue($value);
		}
		if($this->endian==self::LE) {
			throw new \RuntimeException("addInt16 is only compatible with SE byte order.");
		}

		if($this->endian==self::BE) {
			throw new \RuntimeException("addInt16 is only compatible with SE byte order.");
			$this->string .= \IntVal::int16BE()->putValue($value);
		}
	}

	function addUInt16(int $value) {
		if($this->endian==self::SE) {
			$this->string .= \IntVal::uint16SE()->putValue($value);
		}
		if($this->endian==self::LE) {
			$this->string .= \IntVal::uint16LE()->putValue($value);
		}

		if($this->endian==self::BE) {
			$this->string .= \IntVal::uint16BE()->putValue($value);
		}
	}
	
	function addInt32(int $value) {
		if($this->endian==self::SE) {
			$this->string .= \IntVal::int32SE()->putValue($value);
		}
		if($this->endian==self::LE) {
			throw new \RuntimeException("addInt32 is only compatible with SE byte order.");
		}

		if($this->endian==self::BE) {
			throw new \RuntimeException("addInt32 is only compatible with SE byte order.");
			$this->string .= \IntVal::int32BE()->putValue($value);
		}
	}

	function addUInt32(int $value) {
		if($this->endian==self::SE) {
			$this->string .= \IntVal::uint32SE()->putValue($value);
		}
		if($this->endian==self::LE) {
			$this->string .= \IntVal::uint32LE()->putValue($value);
		}

		if($this->endian==self::BE) {
			$this->string .= \IntVal::uint32BE()->putValue($value);
		}
	}

	function addInt64(int $value) {
		if($this->endian==self::SE) {
			$this->string .= \IntVal::int64SE()->putValue($value);
		return;
		}
	throw new \RuntimeException("addInt64 is only compatible with SE byte order.");
	}

	function addUInt64(int $value) {
		if($this->endian==self::SE) {
			$this->string .= \IntVal::uint64SE()->putValue($value);
		}
		if($this->endian==self::LE) {
			$this->string .= \IntVal::uint64LE()->putValue($value);
		}

		if($this->endian==self::BE) {
			$this->string .= \IntVal::uint64BE()->putValue($value);
		}
	}
	
	function addStringC(int $length, string $string) {
		if(strlen($string)>$length) {
			throw new \RuntimeException("string longer than given length of ".$length);
		}
		$this->string .= str_pad($string, $length+1, chr(0));
	}
	
	function getBinary(): string {
		return $this->string;
	}
	
}
