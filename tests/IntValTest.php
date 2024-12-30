<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class IntValTest extends TestCase {
	function testWriteINT8(): void {
		$writer = IntVal::int8();
		$this->assertEquals($writer->putValue(-3), chr(253));
	}
	
	function testWriteUINT8(): void {
		$writer = IntVal::uint8();
		$this->assertEquals($writer->putValue(253), chr(253));
	}

	function testReadINT8(): void {
		$writer = IntVal::int8();
		$this->assertEquals($writer->getValue(chr(253)), -3);
	}
	
	function testReadUINT8(): void {
		$writer = IntVal::uint8();
		$this->assertEquals($writer->getValue(chr(253)), 253);
	}

	function testWriteUINT16BE(): void {
		$intval = IntVal::uint16BE();
		$this->assertEquals(2122, $intval->getValue(chr(8).chr(74)));
	}
	
	function testWriteUINT16LE(): void {
		$intval = IntVal::uint16LE();
		$this->assertEquals(2122, $intval->getValue(chr(74).chr(8)));
	}

	function testReadINT16BE(): void {
		$intval = IntVal::uint16BE();
		$this->assertEquals($intval->putValue(2122), chr(8).chr(74));
	}
	
	function testReadUINT16LE(): void {
		$intval = IntVal::uint16LE();
		$this->assertEquals($intval->putValue(2122), chr(74).chr(8));
	}

	function testReadUINT32BE(): void {
		$intval = IntVal::uint32BE();
		$this->assertEquals(1627472129, $intval->getValue(chr(97).chr(1).chr(65).chr(1)));
	}
	
	function testReadUINT32LE(): void {
		$intval = IntVal::uint32LE();
		$this->assertEquals(1627472129, $intval->getValue(chr(01).chr(65).chr(01).chr(97)));
	}

	function testWriteINT32BE(): void {
		$intval = IntVal::uint32BE();
		$this->assertEquals($intval->putValue(1627472129), chr(97).chr(1).chr(65).chr(1));
	}
	
	function testWriteUINT32LE(): void {
		$intval = IntVal::uint32LE();
		$this->assertEquals($intval->putValue(1627472129), chr(01).chr(65).chr(01).chr(97));
	}
	
	function testGetEmpty(): void {
		$intval = IntVal::uint8();
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage("binary value is empty.");
		$intval->getValue("");
	}
	
	function testPutEmpty(): void {
		$intval = IntVal::uint8();
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage("parameter \$value is empty");
		$intval->putValue("");
	}

	function testPutWrongType(): void {
		$intval = IntVal::uint8();
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage("parameter \$value is not an integer, but string");
		$intval->putValue("17");
	}
	
}