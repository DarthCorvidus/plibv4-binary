<?php
namespace plibv4\binary;
use plibv4\streams\StreamReader;
use plibv4\streams\StreamWriter;
class String16 implements StringValue{
	#[\Override]
	public static function fromBinary(ByteOrder $byteOrder, StreamReader $streamReader): string{
		$length = Unpack::sInt16($byteOrder, $streamReader->read(2));
	return $streamReader->read($length);
	}

	#[\Override]
	public static function toBinary(ByteOrder $byteOrder, StreamWriter $streamWriter, string $string): void {
		$streamWriter->write(Pack::uInt16($byteOrder, strlen($string)));
		$streamWriter->write($string);
	}
}