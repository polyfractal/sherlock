<?php
/**
 * User: Zachary Tong
 * Date: 2/7/13
 * Time: 5:13 AM
 */

namespace sherlock\components\queries;
use sherlock\components\QueryInterface;
use sherlock\common\exceptions;

/**
 * @method \sherlock\components\queries\Terms field() field($name)  Field to search
 * @method \sherlock\components\queries\Terms term() term($term)    Term to search
 * @method \sherlock\components\queries\Terms minimum_match() minimum_match($int) Optional min number of terms to match
 * @method \sherlock\components\queries\Terms boost() boost($value) Optional boosting for term value. Default = 1
 */
class Terms implements QueryInterface
{
	protected $params = array();

	public function __construct()
	{
		$this->params['boost'] = 1;
		$this->params['minimum_match'] = 1;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return Terms
	 */
	public function __call($name, $arguments)
	{
		$this->params[$name] = $arguments[0];
		return $this;
	}
	public function build()
	{
		$data = $this->params;

		if (!isset($data['field']))
			throw new exceptions\RuntimeException("Field must be set for a Term Query");


		if (!isset($data['terms']))
			throw new exceptions\RuntimeException("Term must be set for a Term Query");

		$ret = 	array("terms" =>
					array($data['field'] =>
						array("value" => $data['terms'],
							"boost" => $data['boost'],
							"minimum_match" => $data['minimum_match']
						)
					)
				);

		return $ret;
	}
}




