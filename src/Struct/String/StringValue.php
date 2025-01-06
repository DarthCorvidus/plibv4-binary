<?php
/**
 * Parent type for anything that accepts a string and returns a string.
 */
namespace plibv4\binary;
use plibv4\streams\StreamReader;
use plibv4\streams\StreamWriter;
interface StringValue extends BinaryValue {
	public static function toBinary(ByteOrder $byteOrder, StreamWriter $streamWriter, string $string): void;
	public static function fromBinary(ByteOrder $byteOrder, StreamReader $streamReader): string;
}
