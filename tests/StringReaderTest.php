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
	
	function testVarStringCEmpty() {
		$expected = "";
		$string = $expected."\0";
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getStringC());
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}
	
	function testVarStringC() {
		$expected = "The cat is on the mat.";
		$string = $expected."\0";
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getStringC());
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}
	
	function testFixedStringC() {
		$expected = "Ultima";
		$string = str_pad($expected, 10, "\0");
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getStringC(10));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}

	function testVarStringCOverflov() {
		$expected = "Ultima VII";
		$string = $expected;
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage("payload size 10, 9 allowed");
		$this->assertEquals($expected, $reader->getStringC(10));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}

	function testVarIndexedStringEmpty() {
		$string = chr(0);
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals("", $reader->getIndexedString(8));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}
	
	function testVarIndexedStringTiny() {
		$expected = "Ultima VII";
		$string = chr(10);
		$string .= $expected;
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getIndexedString(8));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}

	function testVarIndexedStringShort() {
		$expected = "Ultima VII";
		$string = chr(10).chr(0);
		$string .= $expected;
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getIndexedString(16));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}

	function testVarIndexedStringLong() {
		$expected = "Ultima VII";
		$string = chr(10).chr(0).chr(0).chr(0);
		$string .= $expected;
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getIndexedString(32));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}

	function testVarIndexedStringLongLong() {
		$expected = "Ultima VII";
		$string = chr(10).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0);
		$string .= $expected;
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getIndexedString(64));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}
	
	function testVarIndexedFull() {
		$expected = random_bytes(255);
		$string = chr(255);
		$string .= $expected;
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getIndexedString(8));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}

	function testFixedIndexedStringEmpty() {
		$string = str_repeat("\0", 11);
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals("", $reader->getIndexedString(8, 10));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}

	function testFixedIndexedStringTiny() {
		$expected = "Ultima VII";
		$string = chr(10);
		$string .= $expected;
		// It is totally irrelevant what is in the remaining 10 bytes.
		$string .= random_bytes(10);
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getIndexedString(8, 20));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}
	
	function testFixedIndexedStringShort() {
		$expected = "Ultima VII";
		$string = chr(10).chr(0);
		$string .= $expected;
		// It is totally irrelevant what is in the remaining 10 bytes.
		$string .= random_bytes(10);
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getIndexedString(16, 20));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}

	function testFixedIndexedStringLong() {
		$expected = "Ultima VII";
		$string = chr(10).chr(0).chr(0).chr(0);
		$string .= $expected;
		$string .= str_repeat("\0", 10);
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getIndexedString(32, 20));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}

	function testFixedIndexedStringLongLong() {
		$expected = "Ultima VII";
		$string = chr(10).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0);
		$string .= $expected;
		$string .= str_repeat("\0", 10);
		$string .= \IntVal::uint16LE()->putValue(self::UINT16);
		$reader = new StringReader($string, StringReader::LE);
		$this->assertEquals($expected, $reader->getIndexedString(64, 20));
		$this->assertEquals(self::UINT16, $reader->getUInt16());
	}
}