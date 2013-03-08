<?php
/**
 * User: Chad Kouse
 * Date: 3/7/13
 * Time: 4:15 PM
 */

namespace sherlock\components;

class SortComponent
{
    protected $params = array();
    public function __construct()
    {
    }

    public function field($field)
    {
        if (is_array($field))
            $this->params['field'][] = $field;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $ret = array ($this->params['field']);

        return $ret;
    }

    public function toJSON()
    {
        return json_encode($this->toArray());
    }

}