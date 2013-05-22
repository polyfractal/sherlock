<?php
/**
 * User: Brian Seitel
 * Date: 5/16/13
 * Time: 11:45 AM
 * @package Sherlock\components\mappings
 */

namespace Sherlock\components\mappings;

use Sherlock\components;
use Sherlock\common\exceptions;

/**
 * @method \Sherlock\components\mappings\GeoPoint field() field(\string $value)
 * @method \Sherlock\components\mappings\GeoPoint latitude() latitude(\float $value)
 * @method \Sherlock\components\mappings\GeoPoint longitude() longitude(\float $value)
 */
class GeoPoint extends \Sherlock\components\BaseComponent implements \Sherlock\components\MappingInterface
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
            if ($key == 'field') {
                continue;
            }

            $ret[$key] = $value;
        }

        if (!isset($this->params['field'])) {
            throw new \Sherlock\common\exceptions\RuntimeException("Field name must be set for Geo mapping");
        }

        $ret = array($this->params['field'] => array('type' => 'geo_point'));

        return $ret;

    }


    public function getType()
    {
        return $this->type;
    }

}
