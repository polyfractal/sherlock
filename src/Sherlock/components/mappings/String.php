<?php
/**
 * User: Zachary Tong
 * Date: 2013-02-14
 * Time: 10:42 PM
 */
namespace sherlock\components\mappings;

use sherlock\components;
use sherlock\common\exceptions;


/**
 * @method \sherlock\components\mappings\String field() field(\string $value)
 * @method \sherlock\components\mappings\String store() store(\string $value)
 */
class String extends \sherlock\components\BaseComponent implements \sherlock\components\MappingInterface
{
	public function __construct($hashMap = null)
	{

		parent::__construct($hashMap);
	}

	public function toArray()
	{
		$ret = array();
		foreach($this->params as $key => $value)
		{
			if($key == 'field')
				continue;

			$ret[$key] = $value;
		}

		if (!isset($this->params['field']))
			throw new \sherlock\common\exceptions\RuntimeException("Field name must be set for mapping");

		$ret = array($this->params['field'], $ret);
		return $ret;
	}


}

?>