<?php
namespace plibv4\binary;
use PHPUnit\Framework\TestCase;
class StructableValues {
	/** @var array<string, int> */
	private array $ints = array();
	/** @var array<string, string> */
	private array $strings = array();
	/** @var array<string, Structable> */
	private array $structable = array();
	private string $name;
	function __construct(Structable $structable) {
		$this->name = $structable::class;
		$types = array();
		$reflection = new \ReflectionClass($structable);
		$properties = $reflection->getProperties();
		foreach($properties as $value) {
			$property = self::toReflectionProperty($value);
			/**
			 * @psalm-suppress MixedAssignment
			 */
			$propertyValue = $property->getValue($structable);
			if(is_int($propertyValue)) {
				$this->ints[$property->name] = $propertyValue;
			continue;
			}
			if(is_string($propertyValue)) {
				$this->strings[$property->name] = $propertyValue;
			continue;
			}
			// instanceof cannot compare directly against Structable::class
			$tmp = Structable::class;
			if(is_object($propertyValue) and $propertyValue instanceof $tmp) {
				$this->structable[$property->name] = $propertyValue;
			}
		// Values which can not (yet) be handled by StructWriter are ignored.
		}
	}
	
	static function toReflectionProperty(\ReflectionProperty $property): \ReflectionProperty {
		return $property;
	}
	
	public function getString(string $name): string {
		if(!isset($this->strings[$name])) {
			throw new \RuntimeException("object ".$this->name." does not have property 'string \$".$name."'");
		}
		return $this->strings[$name];
	}
	
	public function getInt(string $name): int {
		if(!isset($this->ints[$name])) {
			throw new \RuntimeException("object ".$this->name." does not have property 'int \$".$name."'");
		}
	return $this->ints[$name];
	}

	public function getStructable(string $name): Structable {
		if(!isset($this->structable[$name])) {
			throw new \RuntimeException("object ".$this->name." does not have property 'Structable \$".$name."'");
		}
	return $this->structable[$name];
	}
	
	
}
