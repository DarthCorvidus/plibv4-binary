<?php
namespace plibv4\binary\tests;
use plibv4\binary\Structure;
use plibv4\binary\UnsignedInteger8;
use plibv4\binary\UnsignedInteger32;
/**
 * @psalm-suppress MissingConstructor
 */
class ExampleStruct implements Structure {
	public UnsignedInteger8 $tiny;
	public UnsignedInteger32 $int;
	public function forClass(): string {
		return Example::class;
	}
}