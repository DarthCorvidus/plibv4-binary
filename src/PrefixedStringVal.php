<?php
namespace plibv4\binary;
/**
 * @implements BinVal<string>
*/
class PrefixedStringVal implements BinVal {
	private IntVal $intval;
	private int $length = 0;
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
