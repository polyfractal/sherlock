<?php
/**
 * User: Zachary Tong
 * Date: 3/16/13
 * Time: 10:56 AM
 */

namespace Sherlock\components\facets;

use Analog\Analog;
use Sherlock\common\exceptions\BadMethodCallException;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Histogram
 * @package Sherlock\components\facets
 *
 * @method \Sherlock\components\facets\Histogram facetname() facetname(\string $value)
 * @method \Sherlock\components\facets\Histogram interval() interval(\int $value)
 * @method \Sherlock\components\facets\Histogram time_interval() time_interval(\string $value)
 * @method \Sherlock\components\facets\Histogram key_field() key_field(\string $value)
 * @method \Sherlock\components\facets\Histogram value_field() value_field(\string $value)
 * @method \Sherlock\components\facets\Histogram key_script() key_script(\string $value)
 * @method \Sherlock\components\facets\Histogram value_script() value_script(\string $value)
 * @method \Sherlock\components\facets\Histogram params() params(array $value)
 * @method \Sherlock\components\facets\TermsStats lang() lang(\string $value)
 * @method \Sherlock\components\facets\DateHistogram facet_filter() facet_filter(\Sherlock\components\FilterInterface $value)
 */
class Histogram extends components\BaseComponent implements components\FacetInterface
{
    /**
     * @param null $hashMap
     */
    public function __construct($hashMap = null)
    {

        $this->params['facetname'] = null;
        $this->params['interval'] = null;
        $this->params['time_interval'] = null;
        $this->params['params'] = null;
        $this->params['key_field'] = null;
        $this->params['value_field'] = null;
        $this->params['key_script'] = null;
        $this->params['value_script'] = null;
        $this->params['lang'] = null;
        $this->params['facet_filter'] = null;

        parent::__construct($hashMap);
    }

    /**
     * @param $fieldName
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @return $this
     */
    public function field($fieldName)
    {

        Analog::debug("Histogram->field(".print_r($fieldName, true).")");

        if (is_string($fieldName)) {
            $this->params['field'] = $fieldName;
        } else {
            Analog::error("Field must be a string");
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


        //if the user didn't provide a facetname, use the field as a default name
        if ($this->params['facetname'] === null) {
            $this->params['facetname'] = $this->params['field'];
        }

        if ($this->params['facet_filter'] !== null) {
            $this->params['facet_filter'] = $this->params['facet_filter']->toArray();
        }


        $ret = array (
            $this->params['facetname'] => array(
                "histogram" => array(
                    "field" => $this->params['field'],
                    "interval" => $this->params['interval'],
                    "time_interval" => $this->params['time_interval'],
                    "key_field" => $this->params['key_field'],
                    "value_field" => $this->params['value_field'],
                    "key_script" => $this->params['key_script'],
                    "value_script" => $this->params['value_script'],
                    "params" => $this->params['params'],
                    "lang" => $this->params['lang']
                )
            ),
            "facet_filter" => $this->params['facet_filter']
        );

        return $ret;
    }

}
