<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 5:20 PM
 */
namespace sherlock\components\queries;

use sherlock\components;
use sherlock\common\exceptions;


/**
 * @method \sherlock\components\queries\Term field($name)  Field to search
 * @method \sherlock\components\queries\Term term($term)    Term to search
 * @method \sherlock\components\queries\Term boost($value) Optional boosting for term value. Default = 1
 */
class Term extends \sherlock\components\BaseComponent implements \sherlock\components\QueryInterface
{
	public function __construct()
	{
		$this->params['$boost'] = 1;
	}
}




