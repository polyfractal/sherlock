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
 * @method \Sherlock\components\sorts\Field name() name(\string $value)
 * @method \Sherlock\components\sorts\Field order() order(\string $value) Default: null
 * @method \Sherlock\components\sorts\Field missing() missing(\string $value) Default: null
 * @method \Sherlock\components\sorts\Field ignore_unmapped() ignore_unmapped(\bool $value) Default: null
 */
class Field extends components\BaseComponent implements components\SortInterface
{
    public function __construct($hashMap = null)
    {
        $this->params['order'] = null;
        $this->params['sort_mode'] = null;
        $this->params['order'] = null;
        $this->params['missing'] = null;
        $this->params['ignore_unmapped'] = null;

        parent::__construct($hashMap);
    }

    public function toArray()
    {
        $ret = array (
            $this->params['name'] =>
            array (
                'sort_mode' => $this->params["sort_mode"],
                'order' => $this->params["order"],
                'missing' => $this->params["missing"],
                'ignore_unmapped' => $this->params["ignore_unmapped"],
            ),
        );

        return $ret;
    }

}
