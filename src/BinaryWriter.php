<?php
/**
 * Recurses to a structure as offered by a BinStruct implementation,
 * drawing values from an array as defined by BinStruct.
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */
class BinaryWriter {
	static function toHandle($handle, array $values, BinStruct $model) {
		foreach($model->getNames() as $value) {
			if(!isset($values[$value])) {
				throw new OutOfBoundsException("Key ".$value." does not exist in input array while processing ". get_class($model));
			}
			$input = $values[$value];
			if($model->isBinVal($value)) {
				$binval = $model->getBinVal($value);
				fwrite($handle, $binval->putValue($input));
			}
			if($model->isBinStruct($value)) {
				BinaryWriter::toHandle($handle, $input, $model->getBinStruct($value));
			}
		}
	}
	
	static function toPath($filename, array $values, BinStruct $model) {
		$handle = fopen($filename, "wb");
		self::toHandle($handle, $values, $model);
		fclose($handle);
	}
}

