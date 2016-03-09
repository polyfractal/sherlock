<?php

namespace Sherlock\components\aggregations;

use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Terms
 * @package Sherlock\components\aggregations
 *
 * @method \Sherlock\components\aggregations\Terms aggsname() aggsname(\string $value)
 * @method \Sherlock\components\aggregations\Terms size() size(\string $value)
 * @method \Sherlock\components\aggregations\Terms params() params(array $value)
 * @method \Sherlock\components\aggregations\Terms order() order(\string $value)
 *
 *
 */
class Terms extends components\BaseComponent implements components\AggregationInterface
{
    /**
     * @param null $hashMap
     */
    public function __construct($hashMap = null)
    {
        $this->params['aggsname']    = null;
        $this->params['order']       = null;
        $this->params['params']       = null;
        $this->params['size']         = null;

        parent::__construct($hashMap);
    }


    /**
     * @param $fieldName
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @return $this
     */
    public function fields($fieldName)
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
            "terms"  => array(
                "field" => $this->params['field']
            )
        );

        if ($this->params['size'] !== null) {
            $params['terms']["size"] = $this->params['size'];
        }
        if ($this->params['order'] !== null) {
            $params['terms']["order"] = $this->params['order'];
        }
        $ret = array(
            $this->params['aggsname'] => $params
        );

        return $ret;
    }

}
