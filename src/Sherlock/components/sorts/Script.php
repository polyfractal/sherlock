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
 */

class Script extends components\BaseComponent implements components\SortInterface
{

    public function __construct($hashMap = null)
    {
        $this->params['script'] = null;

        parent::__construct($hashMap);
    }

    public function toArray()
    {
        $ret = array(
            '_script' =>
            array(
                'script' => $this->params["script"],
            ),
        );

        return $ret;
    }

}
