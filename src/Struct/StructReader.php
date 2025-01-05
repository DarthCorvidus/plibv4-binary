<?php
namespace plibv4\binary;
use plibv4\streams\StreamReader;
class StructReader {
	private StreamReader $streamReader;
	private ByteOrder $byteOrder;
	function __construct(ByteOrder $byteOrder, StreamReader $streamReader) {
		$this->streamReader = $streamReader;
		$this->byteOrder = $byteOrder;
	}
	
	function readClass(Structure $structure): Structable {
		$structureTypes = StructWriter::getStructureTypes($structure);
		foreach($structureTypes as $propertyName => $typeName) {
			#echo "Checking if ".$typeName." implements BinaryValue".PHP_EOL;
			if(StructWriter::implements($typeName, BinaryValue::class)) {
				#echo "Calling ".$typeName."::toBinary".PHP_EOL;
				$values[$propertyName] = call_user_func_array(array($typeName, "fromBinary"), array($this->byteOrder, $this->streamReader));
			continue;
			}
		throw new \RuntimeException(sprintf("No way to handle structure property type '%s'", $typeName));
		}
		$reflection = new \ReflectionClass($structure->forClass());
		$instance = $reflection->newInstanceWithoutConstructor();
	
		foreach($reflection->getProperties() as $value) {
			$properties[$value->getName()] = $value;
		}
		foreach($values as $key => $value) {
			$properties[$key]->setValue($instance, $value);
		}
	return $instance;
	}
}
