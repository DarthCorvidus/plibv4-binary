<?php
namespace plibv4\binary;
use plibv4\streams\StreamWriter;
use plibv4\streams\StreamReader;
class UnsignedInteger32 implements IntegerValue {
	public static function fromBinary(ByteOrder $byteOrder, StreamReader $streamReader): int {
		return Unpack::uInt32($byteOrder, $streamReader->read(4));
	}

	public static function toBinary(ByteOrder $byteOrder, StreamWriter $streamWriter, int $int): void {
		$streamWriter->write(Pack::uInt32($byteOrder, $int));
	}
}