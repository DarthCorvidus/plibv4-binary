<?php
/**
 * Skips over a certain amount of data when reading, returning an empty string.
 * Writes a certain amount of null bytes when writing.
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */

class SkipVal implements BinVal {
	private $length = 0;
	function __construct(int $length) {
		$this->length = $length;
	}

	public function getLength(): int {
		return $this->length;
	}

	public function getValue(string $string) {
		return "";
	}

	public function putValue($value): string {
		return str_repeat("\0", $this->length);
	}
}
