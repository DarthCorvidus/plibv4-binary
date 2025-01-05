<?php
declare(strict_types=1);
use plibv4\streams\StringReader;
use plibv4\streams\StringWriter;
use plibv4\binary\UnsignedInteger8;
use plibv4\binary\ByteOrder;
use PHPUnit\Framework\TestCase;
class UnsignedInteger8Test extends TestCase {
	function testToBinary(): void {
		$expected = chr(15).chr(28);
		$streamWriter = new StringWriter();
		UnsignedInteger8::toBinary(ByteOrder::BE, $streamWriter, 15);
		UnsignedInteger8::toBinary(ByteOrder::BE, $streamWriter, 28);
		$this->assertSame($expected, $streamWriter->getString());
	}
	
	function testFromBinary(): void {
		$expected = [15, 28];
		$streamReader = new StringReader(chr(15).chr(28));
		$actual = [];
		$actual[] = UnsignedInteger8::fromBinary(ByteOrder::BE, $streamReader);
		$actual[] = UnsignedInteger8::fromBinary(ByteOrder::BE, $streamReader);
		$this->assertSame($expected, $actual);
	}
}
