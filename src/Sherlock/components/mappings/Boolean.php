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
 * @method \Sherlock\components\mappings\Boolean field() field(\string $value)
 * @method \Sherlock\components\mappings\Boolean store() store(\string $value)
 * @method \Sherlock\components\mappings\Boolean index() index(\string $value)
 * @method \Sherlock\components\mappings\Boolean index_name() index_name(\string $value)
 * @method \Sherlock\components\mappings\Boolean boost() boost(\float $value)
 * @method \Sherlock\components\mappings\Boolean null_value() null_value(\string $value)
 * @method \Sherlock\components\mappings\Boolean include_in_all() include_in_all(\bool $value)
 *
 */
class Boolean extends \Sherlock\components\BaseComponent implements \Sherlock\components\MappingInterface
{
    protected $type;

    public function __construct($type = null, $hashMap = null)
    {
        //if $type is set, we need to wrap the mapping property in a type
        //this is used for multi-mappings on index creation
        if (isset($type)) {
            $this->type = $type;
        }

        $this->params['type'] = 'boolean';
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
            throw new \Sherlock\common\exceptions\RuntimeException("Field name must be set for Boolean mapping");

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
