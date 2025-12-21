<?php
namespace plibv4\binary\tests;
use plibv4\binary\Structure;
use plibv4\binary\UnsignedInteger8;
use plibv4\binary\UnsignedInteger32;
/**
 * @psalm-suppress MissingConstructor
 */
class VacationStruct implements Structure {
	public UnsignedInteger32 $from;
	public UnsignedInteger32 $to;
	public UnsignedInteger8 $approval;
	#[\Override]
	public function forClass(): string {
		return Vacation::class;
	}
}
