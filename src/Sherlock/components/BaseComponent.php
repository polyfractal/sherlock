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
	/**
	 * @var array
	 */
	protected $params = array();

	public function __construct($hashMap = null)
	{
		if (is_array(($hashMap)) && count($hashMap) > 0)
		{
			//merge the provided values with our param array, overwriting defaults where necessary
			$this->params = array_merge($this->params, $hashMap);
		}

	}

	public function __call($name, $arguments)
	{
		\Analog\Analog::log("BaseComponent->".$name."(".print_r($arguments[0]).")", \Analog\Analog::DEBUG);

		if ($name == 'toJSON')
			return $this->toJSON();

		$this->params[$name] = $arguments[0];
		return $this;
	}

	public function toJSON()
	{
		return json_encode($this->toArray());
	}


	abstract function toArray();




}




