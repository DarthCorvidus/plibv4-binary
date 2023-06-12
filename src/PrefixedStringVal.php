<?php
class PrefixedStringVal implements BinVal {
	private $intval;
	private $length;
	function __construct(IntVal $intval) {
		$this->intval = $intval;
	}
	public function getLength(): int {
		return $this->length;
	}

	public function getValue(string $string) {
		$length = $this->intval->getValue($string);
		$this->length = $this->intval->getLength()+$length;
	return substr($string, $this->intval->getLength(), $length);
	}

	public function putValue($value): string {
		$binary = $this->intval->putValue(strlen($value)).$value;
		$this->length = strlen($binary);
	return $binary;
	}

}
