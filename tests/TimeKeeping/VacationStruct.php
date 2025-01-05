<?php
namespace plibv4\binary\tests;
use plibv4\binary\Structure;
use plibv4\binary\UnsignedInteger8;
use plibv4\binary\UnsignedInteger32;
class VacationStruct implements Structure {
	public UnsignedInteger32 $from;
	public UnsignedInteger32 $to;
	public UnsignedInteger8 $approval;
}
