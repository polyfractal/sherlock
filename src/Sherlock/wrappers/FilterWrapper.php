<?php
/**
 * User: Zachary Tong
 * Date: 2/19/13
 * Time: 9:21 PM
 */
namespace sherlock\wrappers;

use sherlock\components\filters;
use sherlock\common\exceptions;


/**
 * @method \sherlock\components\filters\Bool Bool() Bool()
 * @method \sherlock\components\filters\AndFilter AndFilter() AndFilter()
 * @method \sherlock\components\filters\Exists Exists() Exists()
 * @method \sherlock\components\filters\GeoBoundingBox GeoBoundingBox() GeoBoundingBox()
 * @method \sherlock\components\filters\GeoDistance GeoDistance() GeoDistance()
 * @method \sherlock\components\filters\GeoDistanceRange GeoDistanceRange() GeoDistanceRange()
 * @method \sherlock\components\filters\GeoPolygon GeoPolygon() GeoPolygon()
 * @method \sherlock\components\filters\HasChild HasChild() HasChild()
 * @method \sherlock\components\filters\HasParent HasParent() HasParent()
 * @method \sherlock\components\filters\Ids Ids() Ids()
 * @method \sherlock\components\filters\Limit Limit() Limit()
 * @method \sherlock\components\filters\MatchAll MatchAll() MatchAll()
 * @method \sherlock\components\filters\Missing Missing() Missing()
 * @method \sherlock\components\filters\Nested Nested() Nested()
 * @method \sherlock\components\filters\Not Not() Not()
 * @method \sherlock\components\filters\NumericRange NumericRange() NumericRange()
 * @method \sherlock\components\filters\Or Or() Or()
 * @method \sherlock\components\filters\Prefix Prefix() Prefix()
 * @method \sherlock\components\filters\Query Query() Query()
 * @method \sherlock\components\filters\Range Range() Range()
 * @method \sherlock\components\filters\Script Script() Script()
 * @method \sherlock\components\filters\Term Term() Term()
 * @method \sherlock\components\filters\Terms Terms() Terms()
 * @method \sherlock\components\filters\Type Type() Type()
 */
class FilterWrapper
{
	/**
	 * @var \sherlock\components\FilterInterface
	 */
	protected $filter;

	public function __call($name, $arguments)
	{
		$class = '\\sherlock\\components\\filters\\'.$name;

		if (count($arguments) > 0)
			$this->filter =  new $class($arguments[0]);
		else
			$this->filter =  new $class();

		return $this->filter;
	}

	public function __toString()
	{
		return (string)$this->filter;
	}

}