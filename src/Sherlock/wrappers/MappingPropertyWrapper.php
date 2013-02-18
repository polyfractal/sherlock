<?php
/**
 * User: Zachary Tong
 * Date: 2/16/13
 * Time: 8:12 PM
 */

namespace sherlock\wrappers;

use sherlock\components\queries;
use sherlock\common\exceptions;


/**
 * @method \sherlock\components\mappings\String String() String()
 * @method \sherlock\components\mappings\Number Number() Number()
 * @method \sherlock\components\mappings\Date Date() Date()
 * @method \sherlock\components\mappings\Boolean Boolean() Boolean()
 * @method \sherlock\components\mappings\Binary Binary() Binary()
 * @method \sherlock\components\mappings\Object Object() Object()
 */
class MappingPropertyWrapper
{
	/**
	 * @var \sherlock\components\MappingInterface
	 */
	protected $property;
   	protected $type;

	/**
	 * @param string $type
	 * @throws \sherlock\common\exceptions\BadMethodCallException
	 */
	public function __construct($type)
	{
		if (!isset($type))
		{
			\Analog\Analog::log("Type must be set for mapping property.", \Analog\Analog::ERROR);
			throw new \sherlock\common\exceptions\BadMethodCallException("Type must be set for mapping property");
		}

		$this->type = $type;

	}
	public function __call($name, $arguments)
	{
		$class = '\\sherlock\\components\\mappings\\'.$name;

		//Type can be passed in the with constructor, used for multi-mappings on index creation
		//Argument[0] is an optional hashmap to define properties via an array
		if (count($arguments) > 0)
			$this->property =  new $class($this->type, $arguments[0]);
		else
			$this->property =  new $class($this->type);

		return $this->property;
	}



}