<?php
/**
 * User: Zachary Tong
 * Date: 2/7/13
 * Time: 8:30 AM
 */

namespace sherlock\components\queries;
use sherlock\components\QueryInterface;
use sherlock\common\exceptions;

/**
 * @method \sherlock\components\queries\Match field() field($name)  Field to search
 * @method \sherlock\components\queries\Match query() query($term)    query to search
 * @method \sherlock\components\queries\Match boost() boost($value) Optional boosting for term value. Default = 1
 * @method \sherlock\components\queries\Match operator() operator($operator) Optional operator for match query. Default = 'and'
 * @method \sherlock\components\queries\Match analyzer() analyzer($analyzer) Optional analyzer for match query. Default to 'default'
 * @method \sherlock\components\queries\Match fuzziness() fuzziness($value) Optional amount of fuzziness. Default to 0.5
 * @method \sherlock\components\queries\Match fuzzy_rewrite() fuzzy_rewrite($value) Default to 'constant_score_default'
 * @method \sherlock\components\queries\Match lenient() lenient($value) Default to 1
 * @method \sherlock\components\queries\Match max_expansions() max_expansions($value) Default to 100
 * @method \sherlock\components\queries\Match minimum_should_match() minimum_should_match($value) Default to 2
 * @method \sherlock\components\queries\Match prefix_length() prefix_length($value) Default to 2
 */

class Match implements QueryInterface
{
	protected $params = array();

	public function __construct()
	{
		$this->params['boost'] = 1;
		$this->params['operator'] = 'and';
		$this->params['analyzer'] = 'default';
		$this->params['fuzziness'] = 0.5;
		$this->params['fuzzy_rewrite'] = 'constant_score_default';
		$this->params['lenient'] = 1;
		$this->params['max_expansions'] = 100;
		$this->params['minimum_should_match'] = 2;
		$this->params['prefix_length'] = 2;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return Match
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
			throw new exceptions\RuntimeException("Field must be set for a Match Query");

		if (!isset($data['query']))
			throw new exceptions\RuntimeException("Query must be set for a Match Query");
		var_dump($data);
		$ret = 	array("match" =>
					array($data['field'] =>
						array("query" => $data['query'],
							"boost" => $data['boost'],
							"operator" => $data['operator'],
							"analyzer" => $data['analyzer'],
							"fuzziness" => $data['fuzziness'],
							"fuzzy_rewrite" => $data['fuzzy_rewrite'],
							"lenient" => $data['lenient'],
							"max_expansions" => $data['max_expansions'],
							"minimum_should_match" => $data['minimum_should_match'],
							"prefix_length" => $data['prefix_length']
						)
					)
				);

		return $ret;
	}
}




