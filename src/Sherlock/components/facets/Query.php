<?php
/**
 * User: Zachary Tong
 * Date: 3/16/13
 * Time: 11:10 AM
 */

namespace Sherlock\components\facets;

use Analog\Analog;
use Sherlock\common\exceptions\BadMethodCallException;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Query
 * @package Sherlock\components\facets
 *
 * @method \Sherlock\components\facets\Query facetname() facetname(\string $value)
 * @method \Sherlock\components\facets\Query query() query(\Sherlock\components\QueryInterface $value)
 * @method \Sherlock\components\facets\DateHistogram facet_filter() facet_filter(\Sherlock\components\FilterInterface $value)
 */
class Query extends components\BaseComponent implements components\FacetInterface
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

        Analog::debug("Query->field(".print_r($fieldName, true).")");

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
            Analog::error("Field parameter is required for a Query Facet");
            throw new RuntimeException("Field parameter is required for a Query Facet");
        }

        if ($this->params['field'] === null) {
            Analog::error("Field parameter may not be null");
            throw new RuntimeException("Field parameter may not be null");
        }

        if (!isset($this->params['query'])) {
            Analog::error("Query parameter is required for a Query Facet");
            throw new RuntimeException("Filter parameter is required for a Query Facet");
        }

        if (!$this->params['query'] instanceof components\QueryInterface) {
            Analog::error("Query parameter must be a Query component");
            throw new RuntimeException("Query parameter must be a Query component");
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
                "query" => $this->params['Query']->toArray(),
                "facet_filter" => $this->params['facet_filter']
            )
        );

        return $ret;
    }

}
