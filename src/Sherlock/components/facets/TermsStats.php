<?php
/**
 * User: Zachary Tong
 * Date: 3/16/13
 * Time: 11:19 AM
 */

namespace Sherlock\components\facets;

use Analog\Analog;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components;

/**
 * Class TermsStats
 * @package Sherlock\components\facets
 *
 * @method \Sherlock\components\facets\TermsStats facetname() facetname(\string $value)
 * @method \Sherlock\components\facets\TermsStats size() size(\int $value)
 * @method \Sherlock\components\facets\TermsStats order() order(\string $value) Default: count
 * @method \Sherlock\components\facets\TermsStats all_terms() all_terms(\bool $value) Default: false
 * @method \Sherlock\components\facets\TermsStats exclude() exclude(array $value)
 * @method \Sherlock\components\facets\TermsStats regex() regex(\string $value)
 * @method \Sherlock\components\facets\TermsStats regex_flags() regex_flags(\int $value)
 * @method \Sherlock\components\facets\TermsStats key_field() key_field(\string $value)
 * @method \Sherlock\components\facets\TermsStats value_field() value_field(\string $value)
 * @method \Sherlock\components\facets\TermsStats key_script() key_script(\string $value)
 * @method \Sherlock\components\facets\TermsStats value_script() value_script(\string $value)
 * @method \Sherlock\components\facets\TermsStats params() params(\string $value)
 * @method \Sherlock\components\facets\TermsStats lang() lang(\string $value)
 * @method \Sherlock\components\facets\DateHistogram facet_filter() facet_filter(\Sherlock\components\FilterInterface $value)
 */
class TermsStats extends components\BaseComponent implements components\FacetInterface
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
        $this->params['key_field'] = null;
        $this->params['value_field'] = null;
        $this->params['key_script'] = null;
        $this->params['value_script'] = null;
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
        Analog::debug("TermsStats->fields(".print_r($args, true).")");

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
        //if the user didn't provide a facetname, use the field as a default name
        if ($this->params['facetname'] === null) {
            $this->params['facetname'] = $this->params['field'][0];
        }

        if ($this->params['facet_filter'] !== null) {
            $this->params['facet_filter'] = $this->params['facet_filter']->toArray();
        }


        $ret = array (
            $this->params['facetname'] => array(
                "terms_stats" => array(
                    "fields" => $this->params['fields'],
                    "order" => $this->params['order'],
                    "all_TermsStats" => $this->params['all_TermsStats'],
                    "size" => $this->params['size'],
                    "exclude" => $this->params['exclude'],
                    "regex" => $this->params['regex'],
                    "regex_flags" => $this->params['regex_flags'],
                    "key_field" => $this->params['key_field'],
                    "value_field" => $this->params['value_field'],
                    "key_script" => $this->params['key_script'],
                    "value_script" => $this->params['value_script'],
                    "params" => $this->params['params'],
                    "lang" => $this->params['lang']
                ),
                "facet_filter" => $this->params['facet_filter']
            )
        );

        return $ret;
    }

}
