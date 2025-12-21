<?php
/**
 * Recurses to a structure as offered by a BinStruct implementation,
 * putting values in an associative array.
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */
namespace plibv4\binary;
use RuntimeException;
class BinaryReader {
	private $model;
	private $pos;
	private $string;
	function __construct(BinStruct $model) {
		$this->model = $model;
	}
	
	private function recurse(BinStruct $model) {
		$values = array();
		foreach($model->getNames() as $value) {
			if($model->isBinVal($value)) {
				$binval = $model->getBinVal($value);
				$bin = substr($this->string, $this->pos);
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

	static function fromHandle($fh, BinStruct $model): array {
		if($fh === FALSE) {
			throw new InvalidArgumentException("Handle is not a valid stream, but boolean false.");
		}
		$values = array();
		foreach($model->getNames() as $value) {
			if($model->isBinVal($value)) {
				$bin = fread($fh, $model->getBinVal($value)->getLength());
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
