<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class StringValTest extends TestCase {
	function testRead(): void {
		$target = "Mars";
		$stringval = new StringVal(8);
		$this->assertEquals($target, $stringval->getValue("Mars\0\0\0\0"));
	}
	
	function testWrite(): void {
		$target = "Mars\0\0\0\0";
		$stringval = new StringVal(8);
		$this->assertEquals($target, $stringval->putValue("Mars"));
	}

	function testReadTruncate(): void {
		$stringval = new StringVal(5);
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("input not null-terminated.");
		$stringval->getValue("Earth");
	}

	function testReadShort(): void {
		$stringval = new StringVal(8);
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("input too short, 8 expected.");
		$stringval->getValue("Earth");
	}
	
	function testWriteTruncate(): void {
		$stringval = new StringVal(6);
		$this->expectException(InvalidArgumentException::class);
		$stringval->putValue("Jupiter");
	}
}