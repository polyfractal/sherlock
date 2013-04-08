<?php
/**
 * User: Zachary Tong
 * Date: 3/14/13
 * Time: 6:27 AM
 */

namespace Sherlock\components\facets;

use Analog\Analog;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**]
 * Class Terms
 * @package Sherlock\components\facets
 *
 * @method \Sherlock\components\facets\Terms facetname() facetname(\string $value)
 * @method \Sherlock\components\facets\Terms size() size(\int $value)
 * @method \Sherlock\components\facets\Terms order() order(\string $value) Default: count
 * @method \Sherlock\components\facets\Terms all_terms() all_terms(\bool $value) Default: false
 * @method \Sherlock\components\facets\Terms exclude() exclude(array $value)
 * @method \Sherlock\components\facets\Terms regex() regex(\string $value)
 * @method \Sherlock\components\facets\Terms regex_flags() regex_flags(\int $value)
 * @method \Sherlock\components\facets\Terms script() script(\string $value)
 * @method \Sherlock\components\facets\Terms script_field() script_field(\string $value)
 * @method \Sherlock\components\facets\Terms params() params(array $value)
 * @method \Sherlock\components\facets\Terms lang() lang(\string $value)
 * @method \Sherlock\components\facets\DateHistogram facet_filter() facet_filter(\Sherlock\components\FilterInterface $value)
 */
class Terms extends components\BaseComponent implements components\FacetInterface
{
    /**
     * @param null $hashMap
     */
    public function __construct($hashMap = null)
    {
        $this->params['order'] = 'count';
        $this->params['all_terms'] = false;

        $this->params['facetname'] = null;
        $this->params['size'] = null;
        $this->params['exclude'] = null;
        $this->params['regex'] = null;
        $this->params['regex_flags'] = null;
        $this->params['script'] = null;
        $this->params['script_field'] = null;
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
        Analog::debug("TermsFacet->fields(".print_r($args, true).")");

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
            Analog::error("Fields parameter is required for a Facet");
            throw new RuntimeException("Fields parameter is required for a Facet");
        }

        if ($this->params['fields'] === null) {
            Analog::error("Fields parameter may not be null");
            throw new RuntimeException("Fields parameter may not be null");
        }

        //if the user didn't provide a facetname, use the (first) field as a default name
        if ($this->params['facetname'] === null) {
            $this->params['facetname'] = $this->params['fields'][0];
        }

        if ($this->params['facet_filter'] !== null) {
            $this->params['facet_filter'] = $this->params['facet_filter']->toArray();
        }

        $ret = array (
            $this->params['facetname'] => array(
                "terms" => array(
                    "fields" => $this->params['fields'],
                    "order" => $this->params['order'],
                    "all_terms" => $this->params['all_terms'],
                    "size" => $this->params['size'],
                    "exclude" => $this->params['exclude'],
                    "regex" => $this->params['regex'],
                    "regex_flags" => $this->params['regex_flags'],
                    "script" => $this->params['script'],
                    "script_field" => $this->params['script_field'],
                    "params" => $this->params['params'],
                    "lang" => $this->params['lang']
                ),
                "facet_filter" => $this->params['facet_filter']
            )
        );

        return $ret;
    }

}
