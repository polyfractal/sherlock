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
 * @method \sherlock\components\mappings\Date field() field(\string $value)
 * @method \sherlock\components\mappings\Date store() store(\string $value)
 * @method \sherlock\components\mappings\Date index() index(\string $value)
 * @method \sherlock\components\mappings\Date format() format(\string $value)
 * @method \sherlock\components\mappings\Date boost() boost(\float $value)
 * @method \sherlock\components\mappings\Date null_value() null_value(\string $value)
 * @method \sherlock\components\mappings\Date precision_step() precision_step(\int $value)
 * @method \sherlock\components\mappings\Date include_in_all() include_in_all(\bool $value)
 * @method \sherlock\components\mappings\Date ignore_malformed() ignore_malformed(\bool $value)
 *
 *
 */
class Date extends \sherlock\components\BaseComponent implements \sherlock\components\MappingInterface
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

		$this->params['type'] = 'date';
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