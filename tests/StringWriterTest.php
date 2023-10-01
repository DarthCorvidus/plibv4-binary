<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use plibv4\Binary\StringWriter;
class StringWriterTest extends TestCase {
	const UINT8  =           15;
	const UINT16 =        33291;
	const UINT32 =   2832231121;
	const UINT64 = 162832231121;
	const INT8   =          -15;
	const INT16  =       -15212;
	const INT32  =  -1932231121;
	const INT64  =-162832231121;
	function testConstruct() {
		$writer = new StringWriter(StringWriter::SE);
		$this->assertInstanceOf(StringWriter::class, $writer);
	}
	
	function testIntSE() {
		$writer = new StringWriter(StringWriter::SE);
		$writer->addInt8(self::INT8);
		$writer->addInt16(self::INT16);
		$writer->addInt32(self::INT32);
		$writer->addInt64(self::INT64);
		$string = $writer->getBinary();
		$this->assertEquals(1+2+4+8, strlen($string));
		$this->assertEquals(self::INT8, \IntVal::int8()->getValue(substr($string, 0, 1)));
		$this->assertEquals(self::INT16, \IntVal::int16SE()->getValue(substr($string, 1, 2)));
		$this->assertEquals(self::INT32, \IntVal::int32SE()->getValue(substr($string, 3, 4)));
		$this->assertEquals(self::INT64, \IntVal::int64SE()->getValue(substr($string, 7, 8)));
	}
	
	function testUIntSE() {
		$writer = new StringWriter(StringWriter::SE);
		$writer->addUInt8(self::UINT8);
		$writer->addUInt16(self::UINT16);
		$writer->addUInt32(self::UINT32);
		$writer->addUInt64(self::UINT64);
		$string = $writer->getBinary();
		$this->assertEquals(1+2+4+8, strlen($string));
		$this->assertEquals(self::UINT8, \IntVal::int8()->getValue(substr($string, 0, 1)));
		$this->assertEquals(self::UINT16, \IntVal::uint16SE()->getValue(substr($string, 1, 2)));
		$this->assertEquals(self::UINT32, \IntVal::uint32SE()->getValue(substr($string, 3, 4)));
		$this->assertEquals(self::UINT64, \IntVal::uint64SE()->getValue(substr($string, 7, 8)));
	}
	
	function testUIntBE() {
		$writer = new StringWriter(StringWriter::BE);
		$writer->addUInt8(self::UINT8);
		$writer->addUInt16(self::UINT16);
		$writer->addUInt32(self::UINT32);
		$writer->addUInt64(self::UINT64);
		$string = $writer->getBinary();
		$this->assertEquals(1+2+4+8, strlen($string));
		$this->assertEquals(self::UINT8, \IntVal::int8()->getValue(substr($string, 0, 1)));
		$this->assertEquals(self::UINT16, \IntVal::uint16BE()->getValue(substr($string, 1, 2)));
		$this->assertEquals(self::UINT32, \IntVal::uint32BE()->getValue(substr($string, 3, 4)));
		$this->assertEquals(self::UINT64, \IntVal::uint64BE()->getValue(substr($string, 7, 8)));
	}

	function testUIntLE() {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt8(self::UINT8);
		$writer->addUInt16(self::UINT16);
		$writer->addUInt32(self::UINT32);
		$writer->addUInt64(self::UINT64);
		$string = $writer->getBinary();
		$this->assertEquals(1+2+4+8, strlen($string));
		$this->assertEquals(self::UINT8, \IntVal::int8()->getValue(substr($string, 0, 1)));
		$this->assertEquals(self::UINT16, \IntVal::uint16LE()->getValue(substr($string, 1, 2)));
		$this->assertEquals(self::UINT32, \IntVal::uint32LE()->getValue(substr($string, 3, 4)));
		$this->assertEquals(self::UINT64, \IntVal::uint64LE()->getValue(substr($string, 7, 8)));
	}

}