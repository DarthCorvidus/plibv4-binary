<?php
/**
 * Get/write a fixed length amount of raw binary data.
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */
namespace plibv4\binary;
class RawVal implements BinVal {
	private $length = 0;
	function __construct(int $length) {
		$this->length = $length;
	}

	public function getLength(): int {
		return $this->length;
	}

	public function getValue(string $string) {
		return substr($string, 0, $this->length);
	}

	public function putValue($value): string {
		return $value;
	}
}
