#!/usr/bin/env php
<?php
/**
 * Eye of the Beholder save game file documentation courtesy of DOS Game Modding
 * wiki, reverse engineered by TheAlmighty Guru.
 * 
 * https://moddingwiki.shikadi.net/wiki/Eye_of_the_Beholder_Save_Game_Format
 */
namespace plibv4\binary;
include(__DIR__."/../vendor/autoload.php");
class BeholderSave implements BinStruct {
	private $values;
	function __construct() {
		$this->values["0"] = new BeholderCharacter();
		$this->values["1"] = new BeholderCharacter();
		$this->values["2"] = new BeholderCharacter();
		$this->values["3"] = new BeholderCharacter();
		$this->values["4"] = new BeholderCharacter();
		$this->values["5"] = new BeholderCharacter();
		$this->values["unknown"] = new RawVal(31643);
	}

	public function getBinVal(string $name): BinVal {
		return $this->values[$name];
	}

	public function getNames(): array {
		return array_keys($this->values);
	}

	public function getBinStruct(string $name): BinStruct {
		return $this->values[$name];
	}

	public function isBinVal(string $name): bool {
		return $this->values[$name] instanceof BinVal;
	}

	public function isBinStruct(string $name): bool {
		return $this->values[$name] instanceof BinStruct;
	}

}

class BeholderCharacter implements BinStruct {
	private $values;
	public function __construct() {
		$this->values["id"] = IntVal::uint8();
		$this->values["active"] = IntVal::uint8();
		$this->values["name"] = new StringVal(11);
		$this->values["str_mod"] = IntVal::uint8();
		$this->values["str_base"] = IntVal::uint8();
		$this->values["str_xmod"] = IntVal::uint8();
		$this->values["str_xbase"] = IntVal::uint8();
		$this->values["int_mod"] = IntVal::uint8();
		$this->values["int_base"] = IntVal::uint8();
		$this->values["wis_mod"] = IntVal::uint8();
		$this->values["wis_base"] = IntVal::uint8();
		$this->values["dex_mod"] = IntVal::uint8();
		$this->values["dex_base"] = IntVal::uint8();
		$this->values["con_mod"] = IntVal::uint8();
		$this->values["con_base"] = IntVal::uint8();
		$this->values["cha_mod"] = IntVal::uint8();
		$this->values["cha_base"] = IntVal::uint8();
		$this->values["hp_current"] = IntVal::uint8();
		$this->values["hp_base"] = IntVal::uint8();
		$this->values["ac"] = IntVal::int8();
		$this->values["unknown"] = IntVal::uint8();
		$this->values["race"] = IntVal::uint8();
		$this->values["class"] = IntVal::uint8();
		$this->values["alignment"] = IntVal::uint8();
		$this->values["portrait"] = IntVal::uint8();
		$this->values["food"] = IntVal::uint8();
		$this->values["level0"] = IntVal::uint8();
		$this->values["level1"] = IntVal::uint8();
		$this->values["level2"] = IntVal::uint8();
		$this->values["xp0"] = IntVal::uint32LE();
		$this->values["xp1"] = IntVal::uint32LE();
		$this->values["xp2"] = IntVal::uint32LE();
		$this->values["unknown1"] = IntVal::uint8();
		$this->values["skip"] = new RawVal(191);
		#$this->values["skip"] = new SkipVal(191);
	}
	
	public function isBinVal(string $name): bool {
		return $this->values[$name] instanceof BinVal;
	}

	public function isBinStruct(string $name): bool {
		return $this->values[$name] instanceof BinStruct;
	}
	
	public function getBinVal(string $name): BinVal {
		return $this->values[$name];
	}

	public function getBinStruct(string $name): BinStruct {
		return $this->values[$name];
	}

	public function getNames(): array {
		return array_keys($this->values);
	}
}

$model = new BeholderSave();
$array = BinaryReader::fromPath(__DIR__."/EOBDATA.SAV", $model);
// copy array into new array and remove raw binary data, for display.
$display = $array;
unset($display[0]["skip"]);
unset($display[1]["skip"]);
unset($display[2]["skip"]);
unset($display[3]["skip"]);
unset($display[4]["skip"]);
unset($display[5]["skip"]);
unset($display["unknown"]);
print_r($display);
// Write to new file, which should be identical to source file.
BinaryWriter::toPath(__DIR__."/EOBDATA.SAV.copy", $array, $model);