<?php
/**
 * User: Zachary Tong
 * Date: 3/16/13
 * Time: 11:06 AM
 */

namespace Sherlock\components\facets;

use Analog\Analog;
use Sherlock\common\exceptions\BadMethodCallException;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Filter
 * @package Sherlock\components\facets
 *
 * @method \Sherlock\components\facets\Filter facetname() facetname(\string $value)
 * @method \Sherlock\components\facets\Filter filter() filter(\Sherlock\components\FilterInterface $value)
 * @method \Sherlock\components\facets\DateHistogram facet_filter() facet_filter(\Sherlock\components\FilterInterface $value)
 */
class Filter extends components\BaseComponent implements components\FacetInterface
{
    /**
     * @param null $hashMap
     */
    public function __construct($hashMap = null)
    {

        $this->params['facetname'] = null;
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

        Analog::debug("Filter->field(".print_r($fieldName, true).")");

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
        if (!isset($this->params['field'])) {
            Analog::error("Field parameter is required for a Filter Facet");
            throw new RuntimeException("Field parameter is required for a Filter Facet");
        }

        if ($this->params['field'] === null) {
            Analog::error("Field parameter may not be null");
            throw new RuntimeException("Field parameter may not be null");
        }

        if (!isset($this->params['filter'])) {
            Analog::error("Filter parameter is required for a Filter Facet");
            throw new RuntimeException("Filter parameter is required for a Filter Facet");
        }

        if (!$this->params['filter'] instanceof components\FilterInterface) {
            Analog::error("Filter parameter must be a Filter component");
            throw new RuntimeException("Filter parameter must be a Filter component");
        }

        //if the user didn't provide a facetname, use the field as a default name
        if ($this->params['facetname'] === null) {
            $this->params['facetname'] = $this->params['field'];
        }

        if ($this->params['facet_filter'] !== null) {
            $this->params['facet_filter'] = $this->params['facet_filter']->toArray();
        }

        $ret = array (
            $this->params['facetname'] => array(
                "filter" => $this->params['filter']->toArray(),
                "facet_filter" => $this->params['facet_filter']
            )
        );

        return $ret;
    }

}
