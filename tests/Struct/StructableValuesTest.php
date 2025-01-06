<?php
namespace plibv4\binary\tests;
use \plibv4\binary\StructableValues;
use PHPUnit\Framework\TestCase;
class StructableValuesTest extends TestCase {
	function testConstruct(): void {
		$example = new Example(15, 150000, "Testing an example");
		$structableValues = new StructableValues($example);
		$this->assertInstanceOf(StructableValues::class, $structableValues);
	}
	
	function testGetInt(): void {
		$example = new Example(15, 150000, "Testing an example");
		$structableValues = new StructableValues($example);
		$this->assertSame(15, $structableValues->getInt("tiny"));
		$this->assertSame(150000, $structableValues->getInt("int"));
	}
	
	function testGetString(): void {
		$example = new Example(15, 150000, "Testing an example");
		$structableValues = new StructableValues($example);
		$this->assertSame("Testing an example", $structableValues->getString("string"));
	}

	function testGetNonexistingString(): void {
		$example = new Example(15, 150000, "Testing an example");
		$structableValues = new StructableValues($example);
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage("object plibv4\\binary\\tests\\Example does not have property 'string \$tiny'");
		$this->assertSame("Testing an example", $structableValues->getString("tiny"));
	}

}
