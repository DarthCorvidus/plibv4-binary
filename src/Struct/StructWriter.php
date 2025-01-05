<?php
namespace plibv4\binary;
use plibv4\streams\StreamWriter;
class StructWriter {
	private ByteOrder $byteOrder;
	private StreamWriter $streamWriter;
	function __construct(ByteOrder $byteOrder, StreamWriter $streamWriter) {
		$this->byteOrder = $byteOrder;
		$this->streamWriter = $streamWriter;
	}
	
	static function toReflectionProperty(\ReflectionProperty $property): \ReflectionProperty {
		return $property;
	}

	static function implements($className, $implementName) {
		return in_array($implementName, class_implements($className));
	}
	
	static function getStructureTypes(Structure $structure): array {
		$types = array();
		$reflection = new \ReflectionClass($structure);
		$properties = $reflection->getProperties();
		foreach($properties as $value) {
			$property = self::toReflectionProperty($value);
			$typeName = $property->getType()->__toString();
			if(self::implements($typeName, BinaryValue::class)) {
				$types[$property->name] = $property->getType()->__toString();
			continue;
			}
		throw new \Exception($typeName." of property ".$property->name." cannot be handled");
		}
	return $types;
	}
	
	private function getStructableTypes(Structable $structable) {
		$types = array();
		$reflection = new \ReflectionClass($structable);
		$properties = $reflection->getProperties();
		foreach($properties as $value) {
			$property = $this->toReflectionProperty($value);
			$types[$property->name] = $property->getValue($structable);
		}
	return $types;
	}
	
	public function writeClass(Structable $structable) {
		$structure = $structable->getStructure();
		$structureTypes = $this->getStructureTypes($structure);
		$structableTypes = $this->getStructableTypes($structable);
		foreach($structureTypes as $propName => $className) {
			$propValue = $structableTypes[$propName];
			call_user_func(array($className, "toBinary"), $this->byteOrder, $this->streamWriter, $propValue);
		}
	}
}
