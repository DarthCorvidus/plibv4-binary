<?php
/**
 * Recurses to a structure as offered by a BinStruct implementation,
 * putting values in an associative array.
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */
namespace plibv4\binary;
use RuntimeException;
use InvalidArgumentException;
class BinaryReader {
	private BinStruct $model;
	private int $pos = 0;
	private string $string = "";
	function __construct(BinStruct $model) {
		$this->model = $model;
	}
	
	private function recurse(BinStruct $model): array {
		$values = array();
		foreach($model->getNames() as $value) {
			if($model->isBinVal($value)) {
				$binval = $model->getBinVal($value);
				$bin = substr($this->string, $this->pos);
				/** @psalm-suppress MixedAssignment */
				$values[$value] = $binval->getValue($bin);
				$this->pos += $binval->getLength();
			}
			if($model->isBinStruct($value)) {
				$values[$value] = $this->recurse($model->getBinStruct($value));
			}
			
		}
	return $values;
	}
	
	function fromString(string $string): array {
		$this->string = $string;
		$this->pos = 0;
	return $this->recurse($this->model);
	}

	/**
	 * 
	 * @param resource $fh
	 * @param BinStruct $model
	 * @return array
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 */
	static function fromHandle(mixed $fh, BinStruct $model): array {
		/**
		 * Technically correct, but it would be unreasonable to allow
		 * resource|false.
		 * @psalm-suppress DocblockTypeContradiction
		 */
		if($fh === false) {
			throw new InvalidArgumentException("Handle is not a valid stream, but boolean false.");
		}
		$values = array();
		foreach($model->getNames() as $value) {
			if($model->isBinVal($value)) {
				$bin = fread($fh, $model->getBinVal($value)->getLength());
				if($bin === false) {
					throw new RuntimeException("unable to read from handle");
				}
				/** 
				 * In my opinion impossible/not reasonable to resolve, as the
				 * core design of BinaryReader is to return a typical
				 * multidimensional array which may contain anything.
				 * @psalm-suppress MixedAssignment 
				 */
				$values[$value] = $model->getBinVal($value)->getValue($bin);
			}
			if($model->isBinStruct($value)) {
				$values[$value] = BinaryReader::fromHandle($fh, $model->getBinStruct($value));
			}
			
		}
	return $values;
	}
	
	static function fromPath(string $filename, BinStruct $model): array {
		$handle = fopen($filename, "rb");
		if($handle===FALSE) {
			throw new RuntimeException("Could not open file ".$filename);
		}
		$values = self::fromHandle($handle, $model);
		fclose($handle);
	return $values;
	}
}
