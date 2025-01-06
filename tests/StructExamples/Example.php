<?php
namespace plibv4\binary\tests;
use plibv4\binary\Structure;
use plibv4\binary\Structable;
class Example implements Structable {
	private int $tiny;
	private int $int;
	private string $string;
	private ExampleSub $subclass;
	function __construct(int $tiny, int $int, string $string, ExampleSub $subclass) {
		$this->tiny = $tiny;
		$this->int = $int;
		$this->string = $string;
		$this->subclass = $subclass;
	}
	
	public function getStructure(): Structure {
		return new ExampleStruct();
	}

	public function onLoadClass(): void {
		
	}
}
