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
 * @method \sherlock\components\mappings\Boolean field() field(\string $value)
 * @method \sherlock\components\mappings\Boolean store() store(\string $value)
 * @method \sherlock\components\mappings\Boolean index() index(\string $value)
 * @method \sherlock\components\mappings\Boolean index_name() index_name(\string $value)
 * @method \sherlock\components\mappings\Boolean boost() boost(\float $value)
 * @method \sherlock\components\mappings\Boolean null_value() null_value(\string $value)
 * @method \sherlock\components\mappings\Boolean include_in_all() include_in_all(\bool $value)
 *
 */
class Boolean extends \sherlock\components\BaseComponent implements \sherlock\components\MappingInterface
{
	protected $type;

	public function __construct($type = null, $hashMap = null)
	{
		//if $type is set, we need to wrap the mapping property in a type
		//this is used for multi-mappings on index creation
		if (isset($type))
		{
			$this->type = $type;
		}

		$this->params['type'] = 'boolean';
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

		$ret = array($this->params['field'] => $ret);

		if (isset($this->type))
			$ret = array($this->type => array("properties" => $ret));


		return $ret;

	}


}

?>