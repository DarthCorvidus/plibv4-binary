<?php
declare(strict_types=1);
namespace plibv4\binary;
use PHPUnit\Framework\TestCase;
class PackTest extends TestCase {
	const UINT8  =           15; // 15
	const UINT16 =        33291; // 130  11
	const UINT32 =   2832231121; // 168 208 106 209
	const UINT64 = 162832231121; // 0   0   0  37 233 142 170 209
	const INT8   =          -15;
	const INT16  =       -15212;
	const INT32  =  -1932231121;
	const INT64  =-162832231121;
	public function testPackUnsignedInt8(): void {
		$this->assertSame(Pack::uInt8(15), chr(15));
	}
	
	public function testPackSignedInt8(): void {
		$this->assertSame(Pack::sInt8(-15), chr(256-15));
	}

	public function testPackUnsignedInt16BigEndian(): void {
		$this->assertSame(Pack::uInt16(ByteOrder::BE, self::UINT16), chr(130).chr(11));
	}

	public function testPackUnsignedInt16LittleEndian(): void {
		$this->assertSame(Pack::uInt16(ByteOrder::LE, 33291), chr(11).chr(130));
	}

	public function testPackSignedInt16BigEndian(): void {
		$this->assertSame(Pack::sInt16(ByteOrder::BE, -15000), Pack::uInt16(ByteOrder::BE, 65536-15000));
	}

	public function testPackSignedInt16LittleEndian(): void {
		$this->assertSame(Pack::sInt16(ByteOrder::LE, -15000), Pack::uInt16(ByteOrder::LE, 65536-15000));
	}

	public function testPackUnsignedInt32BigEndian(): void {
		$this->assertSame(Pack::uInt32(ByteOrder::BE, 2832231121), chr(168).chr(208).chr(106).chr(209));
	}

	public function testPackUnsignedInt32LittleEndian(): void {
		$this->assertSame(Pack::uInt32(ByteOrder::LE, 2832231121), chr(209).chr(106).chr(208).chr(168));
	}

	public function testPackSignedInt32BigEndian(): void {
		$this->assertSame(Pack::sInt32(ByteOrder::BE, -1832231121), Pack::uInt32(ByteOrder::BE, 4294967296-1832231121));
	}

	public function testPackSignedInt32LittleEndian(): void {
		$this->assertSame(Pack::sInt32(ByteOrder::LE, -1832231121), Pack::uInt32(ByteOrder::LE, 4294967296-1832231121));
	}
	
	public function testPackSignedInt64BigEndian(): void {
		$this->assertSame(Pack::sInt64(ByteOrder::BE, 162832231121), chr(0).chr(0).chr(0).chr(37).chr(233).chr(142).chr(170).chr(209));
	}
	
	public function testPackSignedInt64LittleEndian(): void {
		$this->assertSame(Pack::sInt64(ByteOrder::LE, 162832231121), chr(209).chr(170).chr(142).chr(233).chr(37).chr(0).chr(0).chr(0));
	}
}
