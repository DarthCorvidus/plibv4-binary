<?php
/**
 * Interface for a binary structure, which defines a collection of both BinVals
 * and BinStructs, to define complex and nested binary files.
 * @copyright (c) 2023 Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 */
namespace plibv4\binary;
interface BinStruct {
	/**
	 * get names of entries.
	 * @return list<string> get names of BinStruct/BinVal entries
	 */
	function getNames(): array;
	/**
	 * Should return true if an entry is an instance of BinVal
	 * @param string $name
	 */
	function isBinVal(string $name): bool;
	/**
	 * Should return true if an entry is an instance of BinStruct
	 * @param string $name
	 */
	function isBinStruct(string $name): bool;
	/**
	 * Return entry as BinVal
	 * @param string $name
	 */
	function getBinVal(string $name): BinVal;
	/**
	 * Return entry as BinStruct
	 * @param string $name
	 */
	function getBinStruct(string $name): BinStruct;
}
