<?php
/**
 * Recurses to a structure as offered by a BinStruct implementation,
 * putting values in an associative array.
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */
class BinaryReader {
	static function fromHandle($fh, BinStruct $model): array {
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
		$values = self::fromHandle($handle, $model);
		fclose($handle);
	return $values;
	}
}
