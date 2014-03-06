<?php
/**
 * User: Sam Sullivan
 * Date: 3/5/14
 * Time: 8:11 PM
 * @package Sherlock\components\sorts
 */

namespace Sherlock\components\sorts;

use Sherlock\components;

/**
 * @method \Sherlock\components\sorts\Script script() script(\string $value)
 * @method \Sherlock\components\sorts\Script type() type(\string $value)
 * @method \Sherlock\components\sorts\Script params() params(array $value) Default: array()
 * @method \Sherlock\components\sorts\Script order() order(\string $value) Default: asc
 * @method \Sherlock\components\sorts\Script lang() lang(\string $value) Default: mvel
 */

class Script extends components\BaseComponent implements components\SortInterface
{

    public function __construct($hashMap = null)
    {
        $this->params['script'] = null;
        $this->params['type']   = null;
        $this->params['params'] = array();
        $this->params['order']  = 'asc';
        $this->params['lang']   = 'mvel';

        parent::__construct($hashMap);
    }

    public function toArray()
    {
        $ret = array(
            '_script' =>
            array(
                'script' => $this->params["script"],
                'type'   => $this->params["type"],
                'params' => $this->params["params"],
                'order'  => $this->params["order"],
                'lang'   => $this->params["lang"],
            ),
        );

        return $ret;
    }

}
