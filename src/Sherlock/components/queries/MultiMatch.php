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
 * @method \sherlock\components\queries\MultiMatch fields() fields(array $fieldNames)  Field to search
 * @method \sherlock\components\queries\MultiMatch query() query(string $query)    query to search
 *
 * @method \sherlock\components\queries\MultiMatch boost() boost(float $value) Optional boosting for term value. Default = 1
 * @method \sherlock\components\queries\MultiMatch operator() operator(string $operator) Optional operator for match query. Default = 'and'
 * @method \sherlock\components\queries\MultiMatch analyzer() analyzer(string $analyzer) Optional analyzer for match query. Default to 'default'
 * @method \sherlock\components\queries\MultiMatch fuzziness() fuzziness(float $value) Optional amount of fuzziness. Default to 0.5
 * @method \sherlock\components\queries\MultiMatch fuzzy_rewrite() fuzzy_rewrite(string $value) Default to 'constant_score_default'
 * @method \sherlock\components\queries\MultiMatch lenient() lenient(int $value) Default to 1
 * @method \sherlock\components\queries\MultiMatch max_expansions() max_expansions(int $value) Default to 100
 * @method \sherlock\components\queries\MultiMatch minimum_should_match() minimum_should_match(int $value) Default to 2
 * @method \sherlock\components\queries\MultiMatch prefix_length() prefix_length(int $value) Default to 2
 * @method \sherlock\components\queries\MultiMatch use_dis_max() use_dis_max(int $value) Default to 1
 * @method \sherlock\components\queries\MultiMatch tie_breaker() tie_breaker(float $value) Default to 0.7
 */
class MultiMatch extends \sherlock\components\BaseComponent implements \sherlock\components\QueryInterface
{
	public function __construct($hashMap = null)
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
		$this->params['use_dis_max'] = 1;
		$this->params['tie_breaker'] = 0.7;
		parent::__construct($hashMap);
	}

}








