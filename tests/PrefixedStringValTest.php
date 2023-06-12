<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class PrefixedStringValTest extends TestCase {
	function testPutString8() {
		$string = "Mercury, Venus, Earth, Mars, Jupiter, Saturn, Uranus, Neptun";
		$val = new PrefixedStringVal(IntVal::uint8());
		$this->assertEquals(chr(60).$string, $val->putValue($string));
		$this->assertEquals(60+1, $val->getLength());
	}
	
	function testPutString16() {
		$string = str_repeat(" ", 256);
		$val = new PrefixedStringVal(IntVal::uint16LE());
		$this->assertEquals(chr(0).chr(1).$string, $val->putValue($string));
		$this->assertEquals(256+2, $val->getLength());
	}

	function testPutString32() {
		$string = str_repeat(" ", 65536);
		$val = new PrefixedStringVal(IntVal::uint32LE());
		$this->assertEquals(chr(0).chr(0).chr(1).chr(0).$string, $val->putValue($string));
		$this->assertEquals(65536+4, $val->getLength());
	}
	
	function testGetString8() {
		$expect = "Mercury, Venus, Earth, Mars, Jupiter, Saturn, Uranus, Neptun";
		$string = chr(60)."Mercury, Venus, Earth, Mars, Jupiter, Saturn, Uranus, Neptun";
		$val = new PrefixedStringVal(IntVal::uint8());
		$this->assertEquals($expect, $val->getValue($string));
	}
}