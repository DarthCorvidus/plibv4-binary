<?php
declare(strict_types=1);
namespace plibv4\binary;
use PHPUnit\Framework\TestCase;
class UnpackTest extends TestCase {
	public function testPackUnsignedInt8(): void {
		$this->assertSame(Unpack::uInt8(chr(15)), 15);
	}
	
	public function testPackSignedInt8(): void {
		$this->assertSame(Unpack::sInt8(chr(256-15)), -15);
		$this->assertSame(Unpack::sInt8(chr(15)), 15);
	}
	
	public function testPackUnsignedInt16BigEndian(): void {
		$this->assertSame(33291, Unpack::uInt16(ByteOrder::BE, chr(130).chr(11)));
	}
	
	public function testPackUnsignedInt16LittleEndian(): void {
		$this->assertSame(33291, Unpack::uInt16(ByteOrder::LE, chr(11).chr(130)));
	}

	public function testPackSignedInt16BigEndian(): void {
		$this->assertSame(-15000, Unpack::sInt16(ByteOrder::BE, Pack::uInt16(ByteOrder::BE, 65536-15000)));
	}

	public function testPackSignedInt16LittleEndian(): void {
		$this->assertSame(-15000, Unpack::sInt16(ByteOrder::LE, Pack::uInt16(ByteOrder::LE, 65536-15000)));
	}
	
	public function testPackUnsignedInt32BigEndian(): void {
		$this->assertSame(2832231121, Unpack::uInt32(ByteOrder::BE, chr(168).chr(208).chr(106).chr(209)));
	}

	public function testPackUnsignedInt32LittleEndian(): void {
		$this->assertSame(2832231121, Unpack::uInt32(ByteOrder::LE, chr(209).chr(106).chr(208).chr(168)));
	}
	
	public function testPackSignedInt32BigEndian(): void {
		$this->assertSame(-1832231121, Unpack::sInt32(ByteOrder::BE, Pack::uInt32(ByteOrder::BE, 4294967296-1832231121)));
	}

	public function testPackSignedInt32LittleEndian(): void {
		$this->assertSame(-1832231121, Unpack::sInt32(ByteOrder::LE, Pack::uInt32(ByteOrder::LE, 4294967296-1832231121)));
	}

	public function testPackSignedInt64BigEndianPositive(): void {
		$this->assertSame(162832231121, Unpack::sInt64(ByteOrder::BE, chr(0).chr(0).chr(0).chr(37).chr(233).chr(142).chr(170).chr(209)));
	}
	
	public function testPackSignedInt64LittleEndianPositive(): void {
		$this->assertSame(162832231121, Unpack::sInt64(ByteOrder::LE, chr(209).chr(170).chr(142).chr(233).chr(37).chr(0).chr(0).chr(0)));
	}
	
	public function testPackSignedInt64BigEndianNegative(): void {
		$this->assertSame(-162832231121, Unpack::sInt64(ByteOrder::BE, chr(0xFF).chr(0xFF).chr(0xFF).chr(0xDA).chr(0x16).chr(0x71).chr(0x55).chr(0x2f)));
	}
	
	public function testPackSignedInt64LittleEndianNegative(): void {
		$this->assertSame(-162832231121, Unpack::sInt64(ByteOrder::LE, chr(0x2f).chr(0x55).chr(0x71).chr(0x16).chr(0xda).chr(0xff).chr(0xff).chr(0xff)));
	}

	public function testUnsignedRange8(): void {
		for($i=-0;$i<256;$i++) {
			$packUnpack = Unpack::uInt8(Pack::uInt8($i));
			$this->assertSame($i, $packUnpack);
		}
	}

	public function testSignedRange8(): void {
		for($i=-128;$i<128;$i++) {
			$packUnpack = Unpack::sInt8(Pack::sInt8($i));
			$this->assertSame($i, $packUnpack);
		}
	}

}
