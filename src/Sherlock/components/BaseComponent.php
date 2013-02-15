<?php
/**
 * User: Zachary Tong
 * Date: 2/7/13
 * Time: 5:20 PM
 */

namespace sherlock\components;
use sherlock\common\exceptions;



abstract class BaseComponent
{
	protected $params = array();

	public function __construct($hashMap = null)
	{
		if (is_array(($hashMap)) && count($hashMap) > 0)
		{
			//Raw array hash map provided
			//Easiest way to populate the fields is to just call the magic methods internally
			foreach($hashMap as $key => $value)
			{
				$this->$key($value);
			}
		}

	}

	public function __call($name, $arguments)
	{
		$this->params[$name] = $arguments[0];
		return $this;
	}

	public function __toString()
	{
		return json_encode($this->toArray());
	}

	abstract function toArray();




}




