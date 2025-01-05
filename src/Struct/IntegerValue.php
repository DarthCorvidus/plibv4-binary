<?php
namespace plibv4\binary;
use plibv4\streams\StreamWriter;
use plibv4\streams\StreamReader;
/**
 * Parent type for anything that accepts int and returns int
 */
interface IntegerValue extends BinaryValue {
	public static function toBinary(ByteOrder $byteOrder, StreamWriter $streamWriter, int $int): void;
	public static function fromBinary(ByteOrder $byteOrder, StreamReader $streamReader): int;
}