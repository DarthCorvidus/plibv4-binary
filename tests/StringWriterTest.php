<?php
declare(strict_types=1);
namespace plibv4\binary;
use PHPUnit\Framework\TestCase;
class StringWriterTest extends TestCase {
	const UINT8  =           15;
	const UINT16 =        33291;
	const UINT32 =   2832231121;
	const UINT64 = 162832231121;
	const INT8   =          -15;
	const INT16  =       -15212;
	const INT32  =  -1932231121;
	const INT64  =-162832231121;
	function testConstruct(): void {
		$writer = new StringWriter(StringWriter::SE);
		$this->assertInstanceOf(StringWriter::class, $writer);
	}
	
	function testIntSE(): void {
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
	
	function testUIntSE(): void {
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
	
	function testUIntBE(): void {
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

	function testUIntLE(): void {
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
	
	function testVarStringCZero(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addStringC("");
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(chr(11).chr(130).chr(0).chr(11).chr(130), $writer->getBinary());
	}

	function testVarStringCOne(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addStringC("s");
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(chr(11).chr(130)."s".chr(0).chr(11).chr(130), $writer->getBinary());
	}

	function testVarStringCMore(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addStringC("More");
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(chr(11).chr(130)."More".chr(0).chr(11).chr(130), $writer->getBinary());
	}

	function testFixedStringCZero(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addStringC("", 10);
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+10+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).str_pad("", 10, "\0").chr(11).chr(130), $writer->getBinary());
	}
	
	function testFixedStringCOne(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addStringC("1", 10);
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+10+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).str_pad("1", 10, "\0").chr(11).chr(130), $writer->getBinary());
	}
	
	function testFixedStringCMore(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addStringC("More", 10);
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+10+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).str_pad("More", 10, "\0").chr(11).chr(130), $writer->getBinary());
	}

	function testFixedStringCNine(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addStringC("Ultima VI", 10);
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+10+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).str_pad("Ultima VI", 10, "\0").chr(11).chr(130), $writer->getBinary());
	}

	function testFixedStringCOverflow(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage("strlen 10 larger than allowed payload of 9");
		$writer->addStringC("Ultima VII", 10);
	}
	
	function testVarIndexedStringEmpty(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addString8("");
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+1+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).chr(0).chr(11).chr(130), $writer->getBinary());
	}

	function testVarIndexedString(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addString8("The cat is on the mat.");
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+1+22+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).chr(22)."The cat is on the mat.".chr(11).chr(130), $writer->getBinary());
	}

	function testVarIndexedStringMax(): void {
		$expected = random_bytes(255);
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addString8($expected);
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+1+255+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).chr(255).$expected.chr(11).chr(130), $writer->getBinary());
	}

	function testVarIndexedStringOverflow(): void {
		$expected = random_bytes(256);
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage("string length 256, max index length 255");
		$writer->addString8($expected);
	}

	function testVarIndexedStringShort(): void {
		$expected = random_bytes(4096);
		$writer = new StringWriter(StringWriter::LE);
		$writer->addString16($expected);
		$this->assertEquals(2+4096, strlen($writer->getBinary()));
		$this->assertEquals(chr(0).chr(16).$expected, $writer->getBinary());
	}

	function testVarIndexedStringLong(): void {
		$expected = random_bytes(4096);
		$writer = new StringWriter(StringWriter::LE);
		$writer->addString32($expected);
		$this->assertEquals(4+4096, strlen($writer->getBinary()));
		$this->assertEquals(chr(0).chr(16).chr(0).chr(0).$expected, $writer->getBinary());
	}

	function testVarIndexedStringLongLong(): void {
		$expected = random_bytes(4096);
		$writer = new StringWriter(StringWriter::LE);
		$writer->addString64($expected);
		$this->assertEquals(8+4096, strlen($writer->getBinary()));
		$this->assertEquals(chr(0).chr(16).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).$expected, $writer->getBinary());
	}

	function testFixedIndexedStringEmpty(): void {
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addString8("", 10);
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+1+10+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).chr(0).str_repeat("\0", 10).chr(11).chr(130), $writer->getBinary());
	}

	function testFixedIndexedStringShort(): void {
		$expected = "Ultima VI";
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addString16($expected, 10);
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+2+10+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).chr(9).chr(0).str_pad($expected, 10, "\0").chr(11).chr(130), $writer->getBinary());
	}

	function testFixedIndexedStringLong(): void {
		$expected = "Ultima VI";
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addString32($expected, 10);
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+4+10+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).chr(9).chr(0).chr(0).chr(0).str_pad($expected, 10, "\0").chr(11).chr(130), $writer->getBinary());
	}

	function testFixedIndexedStringLongLong(): void {
		$expected = "Ultima VI";
		$writer = new StringWriter(StringWriter::LE);
		$writer->addUInt16(self::UINT16);
		$writer->addString64($expected, 10);
		$writer->addUInt16(self::UINT16);
		$this->assertEquals(2+8+10+2, strlen($writer->getBinary()));
		$this->assertEquals(chr(11).chr(130).chr(9).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).str_pad($expected, 10, "\0").chr(11).chr(130), $writer->getBinary());
	}
	
	function testFixedIndexedOverflow(): void {
		$expected = "Ultima VIII";
		$writer = new StringWriter(StringWriter::LE);
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage("fixed length 10, string length 11");
		$writer->addString8($expected, 10);
	}
}