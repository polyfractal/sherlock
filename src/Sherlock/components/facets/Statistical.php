<?php
/**
 * User: Zachary Tong
 * Date: 3/16/13
 * Time: 11:15 AM
 */

namespace Sherlock\components\facets;

use Analog\Analog;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Statistical
 * @package Sherlock\components\facets
 *
 * @method \Sherlock\components\facets\Statistical facetname() facetname(\string $value)
 * @method \Sherlock\components\facets\Statistical script() script(\string $value)
 * @method \Sherlock\components\facets\Statistical params() params(array $value)
 * @method \Sherlock\components\facets\Statistical lang() lang(\string $value)
 * @method \Sherlock\components\facets\DateHistogram facet_filter() facet_filter(\Sherlock\components\FilterInterface $value)
 */
class Statistical extends components\BaseComponent implements components\FacetInterface
{
    /**
     * @param null $hashMap
     */
    public function __construct($hashMap = null)
    {
        $this->params['facetname'] = null;
        $this->params['script'] = null;
        $this->params['params'] = null;
        $this->params['lang'] = null;
        $this->params['facet_filter'] = null;

        parent::__construct($hashMap);
    }

    /**
     * @param $queries
     * @return $this
     */
    public function fields($queries)
    {

        $args = func_get_args();
        Analog::debug("Statistical->fields(".print_r($args, true).")");

        //single param, array of fields
        if (count($args) == 1 && is_array($args[0]))
            $args = $args[0];

        foreach ($args as $arg) {
            if (is_string($arg))
                $this->params['fields'][] = $arg;
        }

        return $this;
    }

    /**
     * @throws \Sherlock\common\exceptions\RuntimeException
     * @return array
     */
    public function toArray()
    {
        if (!isset($this->params['fields'])) {
            Analog::error("Fields parameter is required for a Statistical Facet");
            throw new RuntimeException("Fields parameter is required for a Statistical Facet");
        }

        if ($this->params['fields'] === null) {
            Analog::error("Fields parameter may not be null");
            throw new RuntimeException("Fields parameter may not be null");
        }

        //if the user didn't provide a facetname, use the field as a default name
        if ($this->params['facetname'] === null) {
            $this->params['facetname'] = $this->params['field'][0];
        }

        if ($this->params['facet_filter'] !== null) {
            $this->params['facet_filter'] = $this->params['facet_filter']->toArray();
        }


        $ret = array (
            $this->params['facetname'] => array(
                "statistical" => array(
                    "fields" => $this->params['fields'],
                    "script" => $this->params['script'],
                    "params" => $this->params['params'],
                    "lang" => $this->params['lang']
                ),
                "facet_filter" => $this->params['facet_filter']
            )
        );

        return $ret;
    }

}
