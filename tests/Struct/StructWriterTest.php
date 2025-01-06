<?php
declare(strict_types=1);
namespace plibv4\binary\tests;
use plibv4\streams\StringWriter;
use plibv4\binary\StructWriter;
use plibv4\binary\Pack;
use plibv4\binary\Unpack;
use \plibv4\binary\ByteOrder;
use PHPUnit\Framework\TestCase;
class StructWriterTest extends TestCase {
	function testWriteVacation(): void {
		$from = gregoriantojd(12, 23, 2024);
		$to = gregoriantojd(1, 3, 2025);
		$expected = Pack::uInt32(ByteOrder::LE, $from);
		$expected .= Pack::uInt32(ByteOrder::LE, $to);
		$expected .= Pack::uInt8(Approval::APPROVED->value);
		
		$vacation = new Vacation($from, $to);
		$vacation->setApproval(Approval::APPROVED);
		$stringWriter = new StringWriter();
		$structWriter = new StructWriter(ByteOrder::LE, $stringWriter);
		$structWriter->writeClass($vacation);
		$this->assertSame($expected, $stringWriter->getString());
	}
	
	function testWriteExample(): void {
		$expected = Pack::uint8(15);
		$expected .= Pack::uint32(ByteOrder::LE, 150000);
		$expected .= Pack::uint16(ByteOrder::LE, 21);
		$expected .= "The cat is on the mat";

		$example = new Example(15, 150000, "The cat is on the mat");
		
		$stringWriter = new StringWriter();
		$structWriter = new StructWriter(ByteOrder::LE, $stringWriter);
		$structWriter->writeClass($example);
		$this->assertSame($expected, $stringWriter->getString());
	}
}
