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
		if(!class_exists($className)) {
			throw new \Exception("class ".$className." does not exists and is not loadable");
		}
		if(!interface_exists($implementName)) {
			throw new \Exception("interface ".$implementName." does not exists and is not loadable");
		}
		/** @var list<string> */
		$implements = class_implements($className);
		return in_array($implementName, $implements, true);
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
				throw new \RuntimeException($value->__toString()." in ".$structure::class." has no type");
			}
			$typeName = $property->getType()->__toString();
			if(self::implements($typeName, BinaryValue::class)) {
				$types[$property->name] = $property->getType()->__toString();
			continue;
			}
			if(self::implements($typeName, Structure::class)) {
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
			/**
			 * If type laid down in Structure implements Structure itself, open
			 * up new StructWriter.
			 */			
			if($this->implements($className, Structure::class)) {
				$writer = new StructWriter($this->byteOrder, $this->streamWriter);
				$writer->writeClass($structableValues->getStructable($propName));
			continue;
			}
		throw new \RuntimeException("unable to handle Structure property ' ".$className." ".$structure::class."::".$propName."'");
		}
	}
}
