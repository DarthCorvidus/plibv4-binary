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
	
	/**
	 * @param Structure $structure
	 * @return Structable
	 * @throws \RuntimeException
	 */
	function readClass(Structure $structure): Structable {
		$structureTypes = StructWriter::getStructureTypes($structure);
		foreach($structureTypes as $propertyName => $typeName) {
			#echo "Checking if ".$typeName." implements BinaryValue".PHP_EOL;
			if(StructWriter::implements($typeName, BinaryValue::class)) {
				#echo "Calling ".$typeName."::toBinary".PHP_EOL;
				$values[$propertyName] = call_user_func_array(array($typeName, "fromBinary"), array($this->byteOrder, $this->streamReader));
			continue;
			}
			/**
			 * If Structure type implements Structure itself, create an empty
			 * instance of the structure and new instance of $structReader, then
			 * read from binary.
			 */
			if(StructWriter::implements($typeName, Structure::class)) {
				$structReader = new StructReader($this->byteOrder, $this->streamReader);
				$reflection = new \ReflectionClass($typeName);
				$instance = $reflection->newInstanceWithoutConstructor();
				$values[$propertyName] = $structReader->readClass($instance);
			continue;
			}
		throw new \RuntimeException(sprintf("No way to handle structure property type '%s'", $typeName));
		}
		$reflection = new \ReflectionClass($structure->forClass());
		$instance = $reflection->newInstanceWithoutConstructor();
		$strName = Structable::class;
		if(!($instance instanceof $strName)) {
			throw new \RuntimeException($instance."::class does not implement ".Structable::class);
		}

		foreach($reflection->getProperties() as $value) {
			$properties[$value->getName()] = $value;
		}
		foreach($values as $key => $value) {
			$properties[$key]->setValue($instance, $value);
		}
	return $instance;
	}
}
