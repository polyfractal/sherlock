<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 5:20 PM
 */
namespace sherlock\components\queries;
use sherlock\components\QueryInterface;
use sherlock\common\exceptions;


/**
 * @method \sherlock\components\queries\Term field($name)  Field to search
 * @method \sherlock\components\queries\Term term($term)    Term to search
 * @method \sherlock\components\queries\Term boost($value) Optional boosting for term value. Default = 1
 */
class Term implements QueryInterface
{
	protected $params = array();

	public function __construct()
	{
		$this->params['boost'] = 1;
	}

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

		if (!isset($data['term']))
			throw new exceptions\RuntimeException("Term must be set for a Term Query");

		$ret = 	array("term" =>
					array($data['field'] =>
						array("value" => $data['term'],
							"boost" => $data['boost']
						)
					)
				);

		return $ret;
	}
}




