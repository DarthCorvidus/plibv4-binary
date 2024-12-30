#!/usr/bin/env php
<?php
include(__DIR__."/vendor/autoload.php");
class BeholderCharacter implements StructModel {
	private $values;
	public function getBinVal(string $name): \BinaryValue {
		
	}

	public function getStructModel(string $name): \StructModel {
		
	}

	public function getStructNames(): array {
		
	}

	public function getValueNames(): array {
		return array_keys($this->values);
	}
}

class BinaryReader {
	private $values;
	private $model;
	function __construct(string $path, StructModel $model) {
		$this->model = $model;
		$fh = fopen($path, "rb");
		foreach($model->getValueNames() as $value) {
			$bin = fread($fh, $this->model->getBinaryValue($value)->getLength());
			$this->values[$value] = $this->model->getBinaryValue($value)->getValue($bin);
		}
		fclose($fh);
	}
	
	function getArray(): array {
		return $this->values;
	}
}

$reader = new BinaryReader("EOBDATA.SAV");
print_r($reader->getArray());