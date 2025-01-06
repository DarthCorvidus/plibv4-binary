<?php
declare(strict_types=1);
namespace plibv4\binary\tests;
use plibv4\streams\StringWriter;
use plibv4\streams\StringReader;
use plibv4\binary\StructWriter;
use plibv4\binary\StructReader;
use plibv4\binary\Pack;
use plibv4\binary\Unpack;
use plibv4\binary\ByteOrder;
use PHPUnit\Framework\TestCase;
class StructReaderTest extends TestCase {
	function testReadVacation(): void {
		$from = gregoriantojd(12, 23, 2024);
		$to = gregoriantojd(1, 3, 2025);

		$vacation = new Vacation($from, $to);
		$vacation->setApproval(Approval::APPROVED);
		
		$stringWriter = new StringWriter();
		$structWriter = new StructWriter(ByteOrder::LE, $stringWriter);
		$structWriter->writeClass($vacation);
		
		$stringReader = new StringReader($stringWriter->getString());
		$structReader = new StructReader(ByteOrder::LE, $stringReader);
		
		$this->assertEquals($vacation, $structReader->readClass(new VacationStruct()));
	}
	
	function testReadExample(): void {
		$expected = new Example(15, 150000, "The cat is on the mat");
		
		$stringWriter = new StringWriter();
		$structWriter = new StructWriter(ByteOrder::LE, $stringWriter);
		$structWriter->writeClass($expected);
		
		$stringReader = new StringReader($stringWriter->getString());
		$structReader = new StructReader(ByteOrder::LE, $stringReader);
		
		$loaded = $structReader->readClass(new ExampleStruct());
		$this->assertEquals($expected, $loaded);
		

	}
}
