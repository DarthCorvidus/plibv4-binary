<?php
namespace plibv4\binary;
class StringWriter {
	private string $string;
	private int $endian;
	const SE = 1;
	const LE = 2;
	const BE = 3;
	const MAX = array(8 => 255, 16 => 65535, 32 => 4294967295, 64 => 9223372036854775807);
	function __construct(int $endian) {
		if(!in_array($endian, array(self::SE, self::LE, self::BE))) {
			throw new \RuntimeException("invalid byte order, use StringWriter::SE, StringWriter::LE, StringWriter::BE");
		}
		$this->string = "";
		$this->endian = $endian;
	}
	
	function addInt8(int $value): void {
		$this->string .= \IntVal::int8()->putValue($value);
	}

	function addUInt8(int $value): void {
		$this->string .= \IntVal::uint8()->putValue($value);
	}

	function addInt16(int $value): void {
		if($this->endian==self::SE) {
			$this->string .= \IntVal::int16SE()->putValue($value);
		}
		if($this->endian==self::LE) {
			throw new \RuntimeException("addInt16 is only compatible with SE byte order.");
		}

		if($this->endian==self::BE) {
			throw new \RuntimeException("addInt16 is only compatible with SE byte order.");
		}
	}

	function addUInt16(int $value): void {
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
	
	function addInt32(int $value): void {
		if($this->endian==self::SE) {
			$this->string .= \IntVal::int32SE()->putValue($value);
		}
		if($this->endian==self::LE) {
			throw new \RuntimeException("addInt32 is only compatible with SE byte order.");
		}

		if($this->endian==self::BE) {
			throw new \RuntimeException("addInt32 is only compatible with SE byte order.");
		}
	}

	function addUInt32(int $value): void {
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

	function addInt64(int $value): void {
		if($this->endian==self::SE) {
			$this->string .= \IntVal::int64SE()->putValue($value);
		return;
		}
	throw new \RuntimeException("addInt64 is only compatible with SE byte order.");
	}

	function addUInt64(int $value): void {
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
	 * @param int $fixedLength
	 * @throws \RuntimeException
	 */
	function addStringC(string $string, int $fixedLength = 0): void {
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
	
	private function addIndexedString(int $width, string $string, int $fixedLength = 0): void {
		if(!in_array($width, array(8, 16, 32, 64))) {
			throw new \RuntimeException("invalid index width, use 8, 16, 32, 64");
		}
		$len = strlen($string);
		if($fixedLength!==0 && $len>$fixedLength) {
			throw new \RuntimeException("fixed length ".$fixedLength.", string length ".$len);
		}
		$max = self::MAX[$width];
		if($len > $max) {
			throw new \RuntimeException("string length ".$len.", max index length ".$max);
		}
		/**
		 * I am not terribly fond of case...switch, but call_user_func comes
		 * with a hefty price tag.
		 */
		//call_user_func(array($this, "addUint".$width), $len);
		switch ($width) {
			case 8:
				$this->addUInt8($len);
			break;
			case 16:
				$this->addUInt16($len);
			break;
			case 32:
				$this->addUInt32($len);
			break;
			case 64:
				$this->addUInt64($len);
			break;
		}
		//call_user_func(array($this, "addUint".$width), $len);
		if($fixedLength!==0) {
			$this->string .= str_pad($string, $fixedLength, "\0");
		return;
		}
		$this->string .= $string;
	}
	
	/**
	 * Add an indexed string with a maximum length of 255 bytes
	 * @param string $string
	 * @param int $fixedLength
	 */
	function addString8(string $string, int $fixedLength = 0): void {
		$this->addIndexedString(8, $string, $fixedLength);
	}

	/**
	 * Add an indexed string with a maximum length of 65.535 bytes
	 * @param string $string
	 * @param int $fixedLength
	 */
	function addString16(string $string, int $fixedLength = 0): void {
		$this->addIndexedString(16, $string, $fixedLength);
	}

	/**
	 * Add an indexed string with a maximum length of 4.294.967.295 bytes (or
	 * 2.147.483.647 on 32bit-systems, as PHP uses signed integers)
	 * @param string $string
	 * @param int $fixedLength
	 */
	function addString32(string $string, int $fixedLength = 0): void {
		$this->addIndexedString(32, $string, $fixedLength);
	}

	/**
	 * Add an indexed string with a maximum length of 9.223.372.036.854.775.807
	 * (please send me an E-Mail if you found a use case)
	 * @param string $string
	 * @param int $fixedLength
	 */
	function addString64(string $string, int $fixedLength = 0): void {
		$this->addIndexedString(64, $string, $fixedLength);
	}
	

	function getBinary(): string {
		return $this->string;
	}
}
