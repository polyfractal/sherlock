<?php
/**
 * User: Zachary Tong
 * Date: 2/16/13
 * Time: 10:23 PM
 * @package Sherlock\components\mappings
 */

namespace Sherlock\components\mappings;

use Sherlock\components;
use Sherlock\common\exceptions;

/**
 * @method \Sherlock\components\mappings\Number field() field(\string $value)
 * @method \Sherlock\components\mappings\Number store() store(\string $value)
 * @method \Sherlock\components\mappings\Number index() index(\string $value)
 * @method \Sherlock\components\mappings\Number index_name() index_name(\string $value)
 * @method \Sherlock\components\mappings\Number boost() boost(\float $value)
 * @method \Sherlock\components\mappings\Number null_value() null_value(\string $value)
 * @method \Sherlock\components\mappings\Number type() type(\string $value)
 * @method \Sherlock\components\mappings\Number precision_step() precision_step(\int $value)
 * @method \Sherlock\components\mappings\Number include_in_all() include_in_all(\string $value)
 * @method \Sherlock\components\mappings\Number ignore_malformed() ignore_malformed(\bool $value)
 */
class Number extends \Sherlock\components\BaseComponent implements \Sherlock\components\MappingInterface
{
    protected $type;

    public function __construct($type = null, $hashMap = null)
    {
        //if $type is set, we need to wrap the mapping property in a type
        //this is used for multi-mappings on index creation
        if (isset($type)) {
            $this->type = $type;
        }

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
            throw new \Sherlock\common\exceptions\RuntimeException("Field name must be set for Number mapping");

        if (!isset($this->params['type']))
            throw new \Sherlock\common\exceptions\RuntimeException("Field type must be set for Number mapping");

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
