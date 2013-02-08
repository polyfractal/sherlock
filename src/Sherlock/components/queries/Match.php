<?php
/**
 * User: Zachary Tong
 * Date: 2/7/13
 * Time: 8:30 AM
 */

namespace sherlock\components\queries;
use sherlock\components;
use sherlock\common\exceptions;

/**
 * @method \sherlock\components\queries\Match field() field(string $fieldName)  Field to search
 * @method \sherlock\components\queries\Match query() query(string $query)    query to search
 *
 * @method \sherlock\components\queries\Match boost() boost(int $value) Optional boosting for term value. Default = 1
 * @method \sherlock\components\queries\Match operator() operator(string $operator) Optional operator for match query. Default = 'and'
 * @method \sherlock\components\queries\Match analyzer() analyzer(string $analyzer) Optional analyzer for match query. Default to 'default'
 * @method \sherlock\components\queries\Match fuzziness() fuzziness(float $value) Optional amount of fuzziness. Default to 0.5
 * @method \sherlock\components\queries\Match fuzzy_rewrite() fuzzy_rewrite(string $value) Default to 'constant_score_default'
 * @method \sherlock\components\queries\Match lenient() lenient(int $value) Default to 1
 * @method \sherlock\components\queries\Match max_expansions() max_expansions(int $value) Default to 100
 * @method \sherlock\components\queries\Match minimum_should_match() minimum_should_match(int $value) Default to 2
 * @method \sherlock\components\queries\Match prefix_length() prefix_length(int $value) Default to 2
 */
class Match extends \sherlock\components\BaseComponent implements \sherlock\components\QueryInterface
{
	public function __construct()
	{
		$this->params['$boost'] = 1;
		$this->params['$operator'] = 'and';
		$this->params['$analyzer'] = 'default';
		$this->params['$fuzziness'] = 0.5;
		$this->params['$fuzzy_rewrite'] = 'constant_score_default';
		$this->params['$lenient'] = 1;
		$this->params['$max_expansions'] = 100;
		$this->params['$minimum_should_match'] = 2;
		$this->params['$prefix_length'] = 2;
	}

}




