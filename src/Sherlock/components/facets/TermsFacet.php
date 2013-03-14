<?php
/**
 * User: Zachary Tong
 * Date: 3/14/13
 * Time: 6:27 AM
 */


namespace Sherlock\components\facets;

use Analog\Analog;
use Sherlock\components;


/**]
 * Class TermsFacet
 * @package Sherlock\components\facets
 *
 * @method \Sherlock\components\facets\TermsFacet fields() fields(mixed $value)
 * @method \Sherlock\components\facets\TermsFacet facetname() facetname(string $value)
 * @method \Sherlock\components\facets\TermsFacet size() size(int $value)
 * @method \Sherlock\components\facets\TermsFacet order() order(string $value) Default: count
 * @method \Sherlock\components\facets\TermsFacet all_terms() all_terms(bool $value) Default: false
 * @method \Sherlock\components\facets\TermsFacet exclude() exclude(array $value)
 * @method \Sherlock\components\facets\TermsFacet regex() regex(string $value)
 * @method \Sherlock\components\facets\TermsFacet regex_flags() regex_flags(int $value)
 * @method \Sherlock\components\facets\TermsFacet script() script(string $value)
 * @method \Sherlock\components\facets\TermsFacet script_field() script_field(string $value)
 */
class TermsFacet extends components\BaseComponent implements components\FacetInterface
{
	/**
	 * @param null $hashMap
	 */
	public function __construct($hashMap = null)
	{
		$this->params['order'] = 'count';
		$this->params['all_terms'] = false;

		$this->params['size'] = null;
		$this->params['exclude'] = null;
		$this->params['regex'] = null;
		$this->params['regex_flags'] = null;
		$this->params['script'] = null;
		$this->params['script_field'] = null;


		parent::__construct($hashMap);
	}

	/**
	 * @param $queries
	 * @return $this
	 */
	public function fields($queries)
	{

		$args = func_get_args();
		Analog::debug("TermsFacet->fields(".print_r($args, true).")");

		//single param, array of fields
		if (count($args) == 1 && is_array($args[0]))
			$args = $args[0];

		foreach ($args as $arg) {
			if (is_string($arg))
				$this->params['fields'][] = $arg;
		}

		return $this;
	}


	/**
	 * @return array
	 */
	public function toArray()
	{
		//if the user didn't provide a facetname, use the (first) field as a default name
		if ($this->params['facetname'] === null)
			$this->params['facetname'] = $this->params['fields'][0];


		$ret = array (
			$this->params['facetname'] => array(
				"terms" => array(
					"fields" => $this->params['fields'],
					"order" => $this->params['order'],
					"all_terms" => $this->params['all_terms'],
					"size" => $this->params['size'],
					"exclude" => $this->params['exclude'],
					"regex" => $this->params['regex'],
					"regex_flags" => $this->params['regex_flags'],
					"script" => $this->params['script'],
					"script_field" => $this->params['script_field'],
				)
			)
		);

		return $ret;
	}

}
