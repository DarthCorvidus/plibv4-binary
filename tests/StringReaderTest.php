<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use plibv4\Binary\StringReader;
class StringReaderTest extends TestCase {
	const UINT8  =           15;
	const UINT16 =        33291;
	const UINT32 =   2832231121;
	const UINT64 = 162832231121;
	const INT8   =          -15;
	const INT16  =       -15212;
	const INT32  =  -1932231121;
	const INT64  =-162832231121;
	function testConstruct() {
		$writer = new StringReader("", StringReader::SE);
		$this->assertInstanceOf(StringReader::class, $writer);
	}
	
	function testIntSE() {
		$string = \IntVal::int8()->putValue(self::INT8);
		$string .= \IntVal::int16SE()->putValue(self::INT16);
		$string .= \IntVal::int32SE()->putValue(self::INT32);
		$string .= \IntVal::int64SE()->putValue(self::INT64);
		
		$reader = new StringReader($string, StringReader::SE);
		$this->assertEquals(self::INT8, $reader->getInt8());
		$this->assertEquals(self::INT16, $reader->getInt16());
		$this->assertEquals(self::INT32, $reader->getInt32());
		$this->assertEquals(self::INT64, $reader->getInt64());
	}
	
	function testUIntSE() {
		$string = \IntVal::uint8()->putValue(self::UINT8);
		$string .= \IntVal::uint16SE()->putValue(self::UINT16);
		$string .= \IntVal::uint32SE()->putValue(self::UINT32);
		$string .= \IntVal::uint64SE()->putValue(self::UINT64);
		
		$reader = new StringReader($string, StringReader::SE);
		$this->assertEquals(self::UINT8, $reader->getUInt8());
		$this->assertEquals(self::UINT16, $reader->getUInt16());
		$this->assertEquals(self::UINT32, $reader->getUInt32());
		$this->assertEquals(self::UINT64, $reader->getUInt64());
	}

	function testUIntLE() {
		$string = \IntVal::uint8()->putValue(self::UINT8);
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$string .= \IntVal::uint32LE()->putValue(self::UINT32);
		$string .= \IntVal::uint64LE()->putValue(self::UINT64);
		
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals(self::UINT8, $reader->getUInt8());
		$this->assertEquals(self::UINT16, $reader->getUInt16());
		$this->assertEquals(self::UINT32, $reader->getUInt32());
		$this->assertEquals(self::UINT64, $reader->getUInt64());
	}

	function testUIntBE() {
		$string = \IntVal::uint8()->putValue(self::UINT8);
		$string .= \IntVal::uint16BE()->putValue(self::UINT16);
		$string .= \IntVal::uint32BE()->putValue(self::UINT32);
		$string .= \IntVal::uint64BE()->putValue(self::UINT64);
		
		$reader = new StringReader($string, StringReader::BE);
		$this->assertEquals(self::UINT8, $reader->getUInt8());
		$this->assertEquals(self::UINT16, $reader->getUInt16());
		$this->assertEquals(self::UINT32, $reader->getUInt32());
		$this->assertEquals(self::UINT64, $reader->getUInt64());
	}
	
	/*
	function testUIntSE() {
	}
	
	function testUIntBE() {
	}

	function testUIntLE() {
	}
	*/
}