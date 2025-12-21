<?php
/**
 * Recurses to a structure as offered by a BinStruct implementation,
 * drawing values from an array as defined by BinStruct.
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */
namespace plibv4\binary;
use OutOfBoundsException;
use RuntimeException;
class BinaryWriter {
	/**
	 * 
	 * @param array<array-key, mixed> $values
	 * @param BinStruct $model
	 * @return string
	 * @throws OutOfBoundsException
	 */
	static function toString(array $values, BinStruct $model): string {
		$string = "";
		foreach($model->getNames() as $value) {
			if(!isset($values[$value])) {
				throw new OutOfBoundsException("Key ".$value." does not exist in input array while processing ". get_class($model));
			}
			/**
			 * @var array<array-key, mixed>
			 */
			$input = $values[$value];
			if($model->isBinVal($value)) {
				$binval = $model->getBinVal($value);
				$string .= $binval->putValue($input);
			}
			if($model->isBinStruct($value)) {
				$string .= BinaryWriter::toString($input, $model->getBinStruct($value));
			}
		}
	return $string;
	}
	
	/**
	 * 
	 * @param resource $handle
	 * @param array<array-key, mixed> $values
	 * @param BinStruct $model
	 * @return void
	 * @throws OutOfBoundsException
	 */
	static function toHandle(mixed $handle, array $values, BinStruct $model): void {
		foreach($model->getNames() as $value) {
			if(!isset($values[$value])) {
				throw new OutOfBoundsException("Key ".$value." does not exist in input array while processing ". get_class($model));
			}
			/**
			 * @var array<array-key, mixed>
			 */
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
	
	/**
	 * 
	 * @param string $filename
	 * @param array<array-key, mixed> $values
	 * @param BinStruct $model
	 * @return void
	 * @throws RuntimeException
	 */
	static function toPath(string $filename, array $values, BinStruct $model): void {
		$handle = fopen($filename, "wb");
		if($handle === false) {
			throw new RuntimeException("unable to open ".$filename." for writing");
		}
		self::toHandle($handle, $values, $model);
		fclose($handle);
	}
}

