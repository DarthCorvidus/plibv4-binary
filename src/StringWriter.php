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
	/**
	 * C type, null terminated string. If $padlength is 0, the string will be
	 * variable, ie a three byte string will take up four bytes and a six byte
	 * string will take up seven bytes.
	 * @param string $string
	 * @param type $padlength
	 * @throws \RuntimeException
	 */
	function addStringC(string $string, int $fixedLength = 0) {
		if($fixedLength === 0) {
			$this->string .= $string."\0";
		return;
		}
		$len = strlen($string);
		if($len>$fixedLength-1) {
			throw new \RuntimeException("strlen ".$len." larger than allowed payload of ".($fixedLength-1));
		}
		$this->string .= str_pad($string, $fixedLength, "\0");
	}
	
	function addIndexedString(int $width, string $string, int $fixedLength = 0) {
		if(!in_array($width, array(8, 16, 32, 64))) {
			throw new \RuntimeException("invalid index width, use 8, 16, 32, 64");
		}
		$len = strlen($string);
		$max = pow($width, 2)-1;
		if($len > $max) {
			throw new \RuntimeException("string length ".$len.", max index length ".$max);
		}
		call_user_func(array($this, "addUint".$width), $len);
		$this->string .= $string;
	}

	function getBinary(): string {
		return $this->string;
	}
}
