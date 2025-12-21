<?php
namespace plibv4\binary\tests;
use plibv4\binary\Structure;
use plibv4\binary\Structable;
class Vacation implements Structable {
	private int $to;
	private int $from;
	private int $approval = Approval::OPEN->value;
	function __construct(int $from, int $to) {
		$this->from = $from;
		$this->to = $to;
	}
	
	function setApproval(Approval $approval): void {
		$this->approval = $approval->value;
	}
	
	function getApproval(): int {
		return $this->approval;
	}

	#[\Override]
	public function getStructure(): Structure {
		return new VacationStruct();
	}

	#[\Override]
	public function onLoadClass(): void {
		
	}
}

