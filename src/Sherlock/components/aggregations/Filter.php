<?php

namespace Sherlock\components\aggregations;

use Sherlock\common\exceptions\BadMethodCallException;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Filter
 * @package Sherlock\components\aggregations
 *
 * @method \Sherlock\components\aggregations\Filter aggsname() aggsname(\string $value)
 * @method \Sherlock\components\aggregations\Filter filter() filter(\Sherlock\components\FilterInterface $value)
 */
class Filter extends components\aggregations\BaseAggs implements components\AggregationInterface
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
    public function field($fieldName)
    {

        if (is_string($fieldName)) {
            $this->params['field'] = $fieldName;
        } else {
            throw new BadMethodCallException("Field must be a string");
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

        if (!isset($this->params['field'])) {
            throw new RuntimeException("Field parameter is required for a Filter Aggregation");
        }

        if ($this->params['field'] === null) {
            throw new RuntimeException("Field parameter may not be null");
        }

        if (!isset($this->params['filter'])) {
            throw new RuntimeException("Filter parameter is required for a Filter Aggregation");
        }

        if (!$this->params['filter'] instanceof components\FilterInterface) {
            throw new RuntimeException("Filter parameter must be a Filter component");
        }

        //if the user didn't provide a facetname, use the field as a default name
        if ($this->params['aggname'] === null) {
            $this->params['aggname'] = $this->params['field'];
        }


        $params = array(
            "filter"       => $this->params['filter']->toArray()
        );

        if ($this->params['aggs'] !== null) {
            $params["aggs"] = $this->params['aggs']->toArray();
        }

        $ret = array(
            $this->params['aggname'] => $params
        );

        return $ret;
    }

}
