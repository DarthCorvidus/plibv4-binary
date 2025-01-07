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
		$values = array();
		foreach($structureTypes as $propertyName => $typeName) {
			#echo "Checking if ".$typeName." implements BinaryValue".PHP_EOL;
			if(StructWriter::implements($typeName, BinaryValue::class)) {
				#echo "Calling ".$typeName."::toBinary".PHP_EOL;
				/**
				 * @psalm-suppress MixedAssignment
				 */
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
				/**
				 * Psalm is right about this one - it demands 'class-string' but
				 * gets class. Problem is that I can't get class-string; I am
				 * not very happy with all this stringy stuff and thought about
				 * having some wrapper like "Klass" which contains a class
				 * string as given by $instance::class or StructReader::class.
				 * However, this would not exactly increase performance.
				 * @psalm-suppress ArgumentTypeCoercion
				 */
				$reflection = new \ReflectionClass($typeName);
				$instance = $reflection->newInstanceWithoutConstructor();
				/**
				 * I think I can be reasonable sure that $typeName denotes a
				 * class implementing Structure.
				 * @psalm-suppress ArgumentTypeCoercion
				 */
				$values[$propertyName] = $structReader->readClass($instance);
			continue;
			}
		throw new \RuntimeException(sprintf("No way to handle structure property type '%s'", $typeName));
		}
		$reflection = new \ReflectionClass($structure->forClass());
		$instance = $reflection->newInstanceWithoutConstructor();
		$strName = Structable::class;
		if(!($instance instanceof $strName)) {
			throw new \RuntimeException($instance::class."::class does not implement ".Structable::class);
		}
		/**
		 * Yes, we don't know what the type of $value is.
		 * @psalm-suppress MixedAssignment
		 */
		foreach($values as $key => $value) {
			$reflection->getProperty($key)->setValue($instance, $value);
		}
	return $instance;
	}
}
