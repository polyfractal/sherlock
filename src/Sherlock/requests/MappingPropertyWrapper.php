<?php
/**
 * User: Zachary Tong
 * Date: 2/16/13
 * Time: 8:12 PM
 */

namespace sherlock\requests;

use sherlock\components\queries;
use sherlock\common\exceptions;


/**
 * @method \sherlock\components\mappings\String String() String()
 */
class MappingPropertyWrapper
{
	/**
	 * @var \sherlock\components\MappingInterface
	 */
	protected $property;
   	protected $type;

	public function __construct($type = null)
	{
		if (isset($type))
		{
			$this->type = $type;
		}
	}
	public function __call($name, $arguments)
	{
		$class = '\\sherlock\\components\\mappings\\'.$name;

		if (count($arguments) > 0)
			$this->property =  new $class($arguments[0]);
		else
			$this->property =  new $class();

		return $this->property;
	}

	public function toArray()
	{
		$ret = (string)$this->property;
		if (isset($this->type))
		{
			$ret = array($this->type, array("properties",$ret));
		}

		return $ret;
	}


}