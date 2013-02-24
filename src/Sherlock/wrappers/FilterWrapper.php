<?php
/**
 * User: Zachary Tong
 * Date: 2/19/13
 * Time: 9:21 PM
 */
namespace Sherlock\wrappers;

use Sherlock\components\filters;

/**
 * @method \Sherlock\components\filters\Bool Bool() Bool()
 * @method \Sherlock\components\filters\AndFilter AndFilter() AndFilter()
 * @method \Sherlock\components\filters\Exists Exists() Exists()
 * @method \Sherlock\components\filters\GeoBoundingBox GeoBoundingBox() GeoBoundingBox()
 * @method \Sherlock\components\filters\GeoDistance GeoDistance() GeoDistance()
 * @method \Sherlock\components\filters\GeoDistanceRange GeoDistanceRange() GeoDistanceRange()
 * @method \Sherlock\components\filters\GeoPolygon GeoPolygon() GeoPolygon()
 * @method \Sherlock\components\filters\HasChild HasChild() HasChild()
 * @method \Sherlock\components\filters\HasParent HasParent() HasParent()
 * @method \Sherlock\components\filters\Ids Ids() Ids()
 * @method \Sherlock\components\filters\Limit Limit() Limit()
 * @method \Sherlock\components\filters\MatchAll MatchAll() MatchAll()
 * @method \Sherlock\components\filters\Missing Missing() Missing()
 * @method \Sherlock\components\filters\Nested Nested() Nested()
 * @method \Sherlock\components\filters\Not Not() Not()
 * @method \Sherlock\components\filters\NumericRange NumericRange() NumericRange()
 * @method \Sherlock\components\filters\OrFilter OrFilter() OrFilter()
 * @method \Sherlock\components\filters\Prefix Prefix() Prefix()
 * @method \Sherlock\components\filters\Query Query() Query()
 * @method \Sherlock\components\filters\Range Range() Range()
 * @method \Sherlock\components\filters\Script Script() Script()
 * @method \Sherlock\components\filters\Term Term() Term()
 * @method \Sherlock\components\filters\Terms Terms() Terms()
 * @method \Sherlock\components\filters\Type Type() Type()
 */
class FilterWrapper
{
    /**
     * @var \Sherlock\components\FilterInterface
     */
    protected $filter;

    public function __call($name, $arguments)
    {
        $class = '\\Sherlock\\components\\filters\\'.$name;

        if (count($arguments) > 0)
            $this->filter =  new $class($arguments[0]);
        else
            $this->filter =  new $class();

        return $this->filter;
    }

    public function __toString()
    {
        return (string) $this->filter;
    }

}
