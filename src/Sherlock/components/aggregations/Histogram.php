<?php


namespace Sherlock\components\aggregations;


use Sherlock\common\exceptions\BadMethodCallException;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Histogram
 * @package Sherlock\components\aggregations
 *
 * @method \Sherlock\components\aggregations\Histogram aggsname() facetname(\string $value)
 * @method \Sherlock\components\aggregations\Histogram interval() interval(\int $value)
 * @method \Sherlock\components\aggregations\Histogram min_doc_count() time_interval(\string $value)
 */
class Histogram extends BaseAggs implements components\AggregationInterface
{
    /**
     * @param null $hashMap
     */
    public function __construct($hashMap = null)
    {

        $this->params['aggsname']     = null;
        $this->params['interval']      = null;
        $this->params['params']        = null;
        $this->params['min_doc_count']  = null;
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

        //if the user didn't provide a facetname, use the field as a default name
        if ($this->params['aggsname'] === null) {
            $this->params['aggsname'] = $this->params['field'];
        }
        if ($this->params['min_doc_count'] === null) {
            $this->params['min_doc_count'] = 0;
        }

        $params = array(
            "histogram" => array(
                "field"         => $this->params['field'],
                "interval"      => $this->params['interval'],
                "min_doc_count" => $this->params['min_doc_count'],
            )
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
