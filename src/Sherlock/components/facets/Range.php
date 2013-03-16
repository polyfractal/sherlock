<?php
/**
 * User: Zachary Tong
 * Date: 3/16/13
 * Time: 10:34 AM
 */


namespace Sherlock\components\facets;

use Analog\Analog;
use Sherlock\common\exceptions\BadMethodCallException;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;


/**]
 * Class Range
 * @package Sherlock\components\facets
 *
 * @method \Sherlock\components\facets\Range facetname() facetname(\string $value)
 * @method \Sherlock\components\facets\Range ranges() ranges(array $value)
 * @method \Sherlock\components\facets\Range key_field() key_field(\string $value)
 * @method \Sherlock\components\facets\Range value_field() value_field(\string $value)
 * @method \Sherlock\components\facets\Range key_script() key_script(\string $value)
 * @method \Sherlock\components\facets\Range value_script() value_script(string $value)
 * @method \Sherlock\components\facets\Range params() params(array $value)
 * @method \Sherlock\components\facets\Range lang() lang(\string $value)
 */
class Range extends components\BaseComponent implements components\FacetInterface
{
	/**
	 * @param null $hashMap
	 */
	public function __construct($hashMap = null)
	{

		$this->params['facetname'] = null;
		$this->params['ranges'] = null;
		$this->params['key_field'] = null;
		$this->params['value_field'] = null;
		$this->params['key_script'] = null;
		$this->params['value_script'] = null;
		$this->params['params'] = null;
		$this->params['lang'] = null;

		parent::__construct($hashMap);
	}

	/**
	 * @param $fieldName
	 * @throws \Sherlock\common\exceptions\BadMethodCallException
	 * @return $this
	 */
	public function field($fieldName)
	{
		Analog::debug("Range->field(".print_r($fieldName, true).")");

		if (is_string($fieldName)){
			$this->params['field'][] = $fieldName;
		}
		else {
			Analog::error("Field must be a string");
			throw new BadMethodCallException("Field must be a string");
		}


		return $this;
	}


	/**
	 * @throws \Sherlock\common\exceptions\RuntimeException
	 * @return array
	 */
	public function toArray()
	{
		if(!isset($this->params['field'])){
			Analog::error("Field parameter is required for a Range Facet");
			throw new RuntimeException("Field parameter is required for a Range Facet");
		}

		if($this->params['field'] === null){
			Analog::error("Field parameter may not be null");
			throw new RuntimeException("Field parameter may not be null");
		}

		//if the user didn't provide a facetname, use the field as a default name
		if ($this->params['facetname'] === null)
			$this->params['facetname'] = $this->params['fields'];


		$ret = array (
			$this->params['facetname'] => array(
				"range" => array(
					"field" => $this->params['field'],
					"ranges" => $this->params['ranges'],
					"key_field" => $this->params['key_field'],
					"value_field" => $this->params['value_field'],
					"key_script" => $this->params['key_script'],
					"value_script" => $this->params['value_script'],
					"params" => $this->params['params'],
					"lang" => $this->params['lang']
				)
			)
		);

		return $ret;
	}

}
