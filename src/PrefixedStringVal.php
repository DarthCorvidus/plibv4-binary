<?php
namespace plibv4\binary;
class PrefixedStringVal implements BinVal {
	private $intval;
	private $length;
	function __construct(IntVal $intval) {
		$this->intval = $intval;
	}
	#[\Override]
	public function getLength(): int {
		return $this->length;
	}

	#[\Override]
	public function getValue(string $string) {
		$length = $this->intval->getValue($string);
		$this->length = $this->intval->getLength()+$length;
	return substr($string, $this->intval->getLength(), $length);
	}

	#[\Override]
	public function putValue($value): string {
		$binary = $this->intval->putValue(strlen($value)).$value;
		$this->length = strlen($binary);
	return $binary;
	}

}
