<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class IntValTest extends TestCase {
	function testWriteINT8() {
		$writer = IntVal::int8();
		$this->assertEquals($writer->putValue(-3), chr(253));
	}
	
	function testWriteUINT8() {
		$writer = IntVal::uint8();
		$this->assertEquals($writer->putValue(253), chr(253));
	}

	function testReadINT8() {
		$writer = IntVal::int8();
		$this->assertEquals($writer->getValue(chr(253)), -3);
	}
	
	function testReadUINT8() {
		$writer = IntVal::uint8();
		$this->assertEquals($writer->getValue(chr(253)), 253);
	}

	function testWriteUINT16BE() {
		$intval = IntVal::uint16BE();
		$this->assertEquals(2122, $intval->getValue(chr(8).chr(74)));
	}
	
	function testWriteUINT16LE() {
		$intval = IntVal::uint16LE();
		$this->assertEquals(2122, $intval->getValue(chr(74).chr(8)));
	}

	function testReadINT16BE() {
		$intval = IntVal::uint16BE();
		$this->assertEquals($intval->putValue(2122), chr(8).chr(74));
	}
	
	function testReadUINT16LE() {
		$intval = IntVal::uint16LE();
		$this->assertEquals($intval->putValue(2122), chr(74).chr(8));
	}

	function testReadUINT32BE() {
		$intval = IntVal::uint32BE();
		$this->assertEquals(1627472129, $intval->getValue(chr(97).chr(1).chr(65).chr(1)));
	}
	
	function testReadUINT32LE() {
		$intval = IntVal::uint32LE();
		$this->assertEquals(1627472129, $intval->getValue(chr(01).chr(65).chr(01).chr(97)));
	}

	function testWriteINT32BE() {
		$intval = IntVal::uint32BE();
		$this->assertEquals($intval->putValue(1627472129), chr(97).chr(1).chr(65).chr(1));
	}
	
	function testWriteUINT32LE() {
		$intval = IntVal::uint32LE();
		$this->assertEquals($intval->putValue(1627472129), chr(01).chr(65).chr(01).chr(97));
	}
	
}