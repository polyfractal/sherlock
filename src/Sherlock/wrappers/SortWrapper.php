<?php
/**
 * User: Zachary Tong
 * Date: 3/7/13
 * Time: 8:07 PM
 */

namespace Sherlock\wrappers;

use Sherlock\components\sorts;

/**
 * @method \Sherlock\components\sorts\Field Field() Field()
 * @method \Sherlock\components\sorts\GeoDistance GeoDistance() GeoDistance()
 * @method \Sherlock\components\sorts\Script Script() Script()
 */
class SortWrapper
{
    /**
     * @var \Sherlock\components\SortInterface
     */
    protected $query;


    /**
     * @param $name
     * @param $arguments
     *
     * @return \Sherlock\components\SortInterface
     */
    public function __call($name, $arguments)
    {
        $class = '\\Sherlock\\components\\sorts\\' . $name;

        if (count($arguments) > 0) {
            $this->query = new $class($arguments[0]);
        } else {
            $this->query = new $class();
        }

        return $this->query;
    }


    public function __toString()
    {
        return (string)$this->query;
    }

}
