<?php
/**
 * User: Zachary Tong
 * Date: 2/7/13
 * Time: 5:13 AM
 */

namespace sherlock\components\queries;

use sherlock\components;
use sherlock\common\exceptions;

/**
 * @method \sherlock\components\queries\Terms fields() field(string $name)  Field to search
 * @method \sherlock\components\queries\Terms terms() terms(array $terms)    Term to search
 * @method \sherlock\components\queries\Terms minimum_match() minimum_match(int $value) Optional min number of terms to match
 * @method \sherlock\components\queries\Terms boost() boost(float $value) Optional boosting for term value. Default = 1
 */
class Terms extends \sherlock\components\BaseComponent implements \sherlock\components\QueryInterface
{
	public function __construct()
	{
		$this->params['$boost'] = 1;
		$this->params['$minimum_match'] = 1;
	}
}


