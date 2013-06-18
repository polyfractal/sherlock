<?php
/**
 * User: Zachary Tong
 * Date: 3/7/13
 * Time: 8:12 PM
 * @package Sherlock\components\sorts
 */

namespace Sherlock\components\sorts;

use Sherlock\components;

/**
 * @method \Sherlock\components\sorts\GeoDistance name() name(\string $value)
 * @method \Sherlock\components\sorts\GeoDistance order() order(\string $value) Default: asc
 * @method \Sherlock\components\sorts\GeoDistance lat() lat(\float $value) Default: null
 * @method \Sherlock\components\sorts\GeoDistance lon() lon(\float $value) Default: null
 * @method \Sherlock\components\sorts\GeoDistance unit() unit(\string $value) Default: km
 */
class GeoDistance extends components\BaseComponent implements components\SortInterface
{
    public function __construct($hashMap = null)
    {
        $this->params['order']           = 'asc';
        $this->params['unit']            = 'km';

        parent::__construct($hashMap);
    }


    public function toArray()
    {
        $ret = array(
            '_geo_distance' => array(
                $this->params['name'] => array($this->params['lon'], $this->params['lat']),
                'order'           => $this->params["order"],
                'unit' => $this->params["unit"]
            )
        );

        return $ret;
    }

}
