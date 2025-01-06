<?php
namespace plibv4\binary\tests;
use \plibv4\binary\Structable;
use \plibv4\binary\Structure;
class ExampleSub implements Structable{
	private int $amount;
	private string $description;
	function __construct(int $amount, string $description) {
		$this->amount = $amount;
		$this->description = $description;
	}

	public function getStructure(): Structure {
		return new ExampleSubStruct();
	}

	public function onLoadClass(): void {
		
	}
}
