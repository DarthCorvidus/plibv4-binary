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

	static function implements(string $className, string $implementName): bool {
		return in_array($implementName, class_implements($className));
	}
	
	/**
	 * 
	 * @param Structure $structure
	 * @return array<string, string>
	 * @throws \RuntimeException
	 * @throws \Exception
	 */
	static function getStructureTypes(Structure $structure): array {
		$types = array();
		$reflection = new \ReflectionClass($structure);
		$properties = $reflection->getProperties();
		foreach($properties as $value) {
			$property = self::toReflectionProperty($value);
			if($property->getType() === null) {
				throw new \RuntimeException($value." in ".Structure::class." has no type");
			}
			$typeName = $property->getType()->__toString();
			if(self::implements($typeName, BinaryValue::class)) {
				$types[$property->name] = $property->getType()->__toString();
			continue;
			}
		throw new \Exception($typeName." of property ".$property->name." cannot be handled");
		}
	return $types;
	}
	
	public function writeClass(Structable $structable): void {
		$structure = $structable->getStructure();
		$structableValues = new StructableValues($structable);
		$structureTypes = $this->getStructureTypes($structure);
		foreach($structureTypes as $propName => $className) {
			if($this->implements($className, IntegerValue::class)) {
				call_user_func(array($className, "toBinary"), $this->byteOrder, $this->streamWriter, $structableValues->getInt($propName));
			continue;
			}
			if($this->implements($className, StringValue::class)) {
				call_user_func(array($className, "toBinary"), $this->byteOrder, $this->streamWriter, $structableValues->getString($propName));
			continue;
			}
		throw new \RuntimeException("unable to handle Struct property ".$structure::class."::".$propName);
		}
	}
}
