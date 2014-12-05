<?php

namespace Sherlock\components\aggregations;


use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Statistical
 * @package Sherlock\components\aggregations
 *
 * @method \Sherlock\components\aggregations\Statistical aggsname() aggsname(\string $value)
 * @method \Sherlock\components\aggregations\Statistical script() script(\string $value)
 * @method \Sherlock\components\aggregations\Statistical params() params(array $value)
 * @method \Sherlock\components\aggregations\Statistical lang() lang(\string $value)
 */
class Statistical extends components\BaseComponent implements components\AggregationInterface
{
    /**
     * @param null $hashMap
     */
    public function __construct($hashMap = null)
    {
        $this->params['aggsname']    = null;
        $this->params['script']       = null;
        $this->params['params']       = null;
        $this->params['lang']         = null;

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
                        throw new RuntimeException("Fields parameter is required for a Statistical Aggregation");
        }

        if ($this->params['field'] === null) {
                        throw new RuntimeException("Field parameter may not be null");
        }

        //if the user didn't provide a facetname, use the field as a default name
        if ($this->params['aggsname'] === null) {
            $this->params['aggsname'] = $this->params['field'];
        }

        $params = array(
            "stats"  => array(
                "field" => $this->params['field']
            )
        );

        if ($this->params['script'] !== null) {
            $params['stats']["script"] = $this->params['script'];
        }
        if ($this->params['lang'] !== null) {
            $params['stats']["lang"] = $this->params['lang'];
        }
        $ret = array(
            $this->params['aggsname'] => $params
        );

        return $ret;
    }

}
