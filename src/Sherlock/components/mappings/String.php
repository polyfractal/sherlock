<?php
/**
 * User: Zachary Tong
 * Date: 2013-02-14
 * Time: 10:42 PM
 * @package Sherlock\components\mappings
 */
namespace Sherlock\components\mappings;

use Sherlock\components;
use Sherlock\common\exceptions;

/**
 * @method \Sherlock\components\mappings\String field() field(\string $value)
 * @method \Sherlock\components\mappings\String store() store(\string $value)
 * @method \Sherlock\components\mappings\String index() index(\string $value)
 * @method \Sherlock\components\mappings\STring index_name() index_name(\string $value)
 * @method \Sherlock\components\mappings\String term_vector() term_vector(\string $value)
 * @method \Sherlock\components\mappings\String boost() boost(\float $value)
 * @method \Sherlock\components\mappings\String null_value() null_value(\string $value)
 * @method \Sherlock\components\mappings\String omit_norms() omit_norms(\bool $value)
 * @method \Sherlock\components\mappings\String omit_term_freq_and_positions() omit_term_freq_and_positions(\bool $value)
 * @method \Sherlock\components\mappings\String index_options() index_options(\string $value)
 * @method \Sherlock\components\mappings\String analyzer() analyzer(\string $value)
 * @method \Sherlock\components\mappings\String index_analyzer() index_analyzer(\string $value)
 * @method \Sherlock\components\mappings\String search_analyzer() search_analyzer(\string $value)
 * @method \Sherlock\components\mappings\String include_in_all() include_in_all(\bool $value)
 * @method \Sherlock\components\mappings\String ignore_above() ignore_above(\int $value)
 * @method \Sherlock\components\mappings\String position_offset_gap() position_offset_gap(\int $value)
 *
 *
 */
class String extends \Sherlock\components\BaseComponent implements \Sherlock\components\MappingInterface
{
    protected $type;

    public function __construct($type = null, $hashMap = null)
    {
        //if $type is set, we need to wrap the mapping property in a type
        //this is used for multi-mappings on index creation
        if (isset($type)) {
            $this->type = $type;
        }

        $this->params['type'] = 'string';
        parent::__construct($hashMap);
    }

    public function toArray()
    {
        $ret = array();
        foreach ($this->params as $key => $value) {

            if($key == 'field')
                continue;

            $ret[$key] = $value;
        }

        if (!isset($this->params['field']))
            throw new \Sherlock\common\exceptions\RuntimeException("Field name must be set for String mapping");

        $ret = array($this->params['field'] => $ret);

        //if (isset($this->type))
        //	$ret = array($this->type => array("properties" => $ret));
        return $ret;

    }

    public function getType()
    {
        return $this->type;
    }

}
