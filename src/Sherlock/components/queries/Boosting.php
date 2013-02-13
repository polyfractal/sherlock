<?php
/**
 * User: Zachary Tong
 * Date: 2013-02-09
 * Time: 07:18 PM
 * Auto-generated by "generate.php"
 */
namespace sherlock\components\queries;

use sherlock\components;
use sherlock\common\exceptions;


/**
 * @method \sherlock\components\queries\Boosting positive() positive(\sherlock\components\QueryInterface $value)
 * @method \sherlock\components\queries\Boosting negative() negative(\sherlock\components\QueryInterface $value)
 * @method \sherlock\components\queries\Boosting negative_boost() negative_boost(float $value) Default: 0.2

 */
class Boosting extends \sherlock\components\BaseComponent implements \sherlock\components\QueryInterface
{
	public function __construct()
	{
		$this->params['$negative_boost'] = 0.2;

	}
}

?>