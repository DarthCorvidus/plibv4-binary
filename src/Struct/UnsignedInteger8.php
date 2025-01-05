<?php
namespace plibv4\binary;
use plibv4\streams\StreamWriter;
use plibv4\streams\StreamReader;
class UnsignedInteger8 implements IntegerValue {
	public static function fromBinary(ByteOrder $byteOrder, StreamReader $streamReader): int {
		return Unpack::uInt8($streamReader->read(1));
	}

	public static function toBinary(ByteOrder $byteOrder, StreamWriter $streamWriter, int $int): void {
		$streamWriter->write(Pack::uInt8($int));
	}
}
