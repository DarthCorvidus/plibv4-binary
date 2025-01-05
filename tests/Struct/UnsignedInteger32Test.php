<?php
declare(strict_types=1);
use plibv4\streams\StringReader;
use plibv4\streams\StringWriter;
use plibv4\binary\UnsignedInteger32;
use plibv4\binary\ByteOrder;
use PHPUnit\Framework\TestCase;
class UnsignedInteger32Test extends TestCase {
	function testToBinaryBE(): void {
		$expected = chr(0x1A).chr(0x8D).chr(0x6F).chr(0x12);
		$streamWriter = new StringWriter();
		UnsignedInteger32::toBinary(ByteOrder::BE, $streamWriter, 445476626);
		$this->assertSame($expected, $streamWriter->getString());
	}

	function testToBinaryLE(): void {
		$expected = chr(0x12).chr(0x6F).chr(0x8D).chr(0x1A);
		$streamWriter = new StringWriter();
		UnsignedInteger32::toBinary(ByteOrder::LE, $streamWriter, 445476626);
		$this->assertSame($expected, $streamWriter->getString());
	}
	
	function testFromBinaryBE(): void {
		$expected = 445476626;
		$binary = chr(0x1A).chr(0x8D).chr(0x6F).chr(0x12);
		$streamReader = new StringReader($binary);
		$actual = UnsignedInteger32::fromBinary(ByteOrder::BE, $streamReader);
		$this->assertSame($expected, $actual);
	}
	
	function testFromBinaryLE(): void {
		$expected = 445476626;
		$binary = chr(0x12).chr(0x6F).chr(0x8D).chr(0x1A);
		$streamReader = new StringReader($binary);
		$actual = UnsignedInteger32::fromBinary(ByteOrder::LE, $streamReader);
		$this->assertSame($expected, $actual);
	}

}