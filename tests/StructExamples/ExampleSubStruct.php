<?php
namespace plibv4\binary\tests;
use plibv4\binary\Structable;
use plibv4\binary\Structure;
/**
 * @psalm-suppress MissingConstructor
 */
class ExampleSubStruct implements Structure {
	private \plibv4\binary\UnsignedInteger8 $amount;
	private \plibv4\binary\String16 $description; 
	public function forClass(): string {
		return ExampleSub::class;
	}
}
