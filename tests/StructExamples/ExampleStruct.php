<?php
namespace plibv4\binary\tests;
use plibv4\binary\Structure;
use plibv4\binary\UnsignedInteger8;
use plibv4\binary\UnsignedInteger32;
use plibv4\binary\String16;
/**
 * @psalm-suppress MissingConstructor
 */
class ExampleStruct implements Structure {
	public UnsignedInteger8 $tiny;
	public UnsignedInteger32 $int;
	public String16 $string;
	public function forClass(): string {
		return Example::class;
	}
}