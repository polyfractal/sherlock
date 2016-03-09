<?php

namespace Sherlock\components\aggregations;

use Sherlock\common\exceptions\BadMethodCallException;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Filter
 * @package Sherlock\components\aggregations
 */
class Filters extends components\aggregations\BaseAggs implements components\AggregationInterface
{
    /**
     * @param null $hashMap
     */
    public function __construct($hashMap = null)
    {

        $this->params['aggsname']     = null;

        parent::__construct($hashMap);
    }


    /**
     * @param $fieldName
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @return $this
     */
    public function aggsname($aggsName)
    {

        if (is_string($aggsName)) {
            $this->params['aggsname'] = $aggsName;
        } else {
            throw new BadMethodCallException("Field must be a string");
        }

        return $this;
    }

    /**
     * @param  components\FilterInterface | components\FilterInterface | array $values,... - one or more Filters can be specified individually, or an array of filters
     *
     * @return Filters
     */
    public function filters($values)
    {

        $args = func_get_args();

        //single param, array of queries\filters
        if (count($args) == 1 && is_array($args[0])) {
            $args = $args[0];
        }

        foreach ($args as $key => $filter) {
            if ($filter instanceof components\FilterInterface) {
                $this->params['filters']['filters'][$key] = $filter->toArray();
            }
        }

        return $this;
    }


    /**
     * @throws \Sherlock\common\exceptions\RuntimeException
     * @return array
     */
    public function toArray()
    {
        $params = array();

        if (!isset($this->params['aggsname'])) {
            throw new RuntimeException("Aggsname parameter is required for a Filters Aggregation");
        }

        if ($this->params['aggsname'] === null) {
            throw new RuntimeException("Aggsname parameter may not be null");
        }

        if (!isset($this->params['filters'])) {
            throw new RuntimeException("Filter parameter is required for a Filters Aggregation");
        }


        //if the user didn't provide a facetname, use the field as a default name
//        if ($this->params['aggname'] === null) {
//            $this->params['aggname'] = $this->params['field'];
//        }


        $params = array(
            "filters"       => $this->params['filters']
        );

        if ($this->params['aggs'] !== null) {
            $params["aggs"] = $this->params['aggs']->toArray();
        }

        $ret = array(
            $this->params['aggsname'] => $params
        );

        return $ret;
    }

}
