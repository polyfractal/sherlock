<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zach
 * Date: 3/22/13
 * Time: 6:51 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Sherlock\requests;

/**
 * Class Command
 * @package Sherlock\requests
 */
class Command implements CommandInterface
{

    /** @var string */
    public $action;

    /** @var string */
    public $data;

    /** @var string */
    public $id;

    /** @var string */
    public $index;

    /** @var string */
    public $type;

    /**
     * @return string
     */
    public function getURI()
    {
        $uri = '/'.$this->index;

        if (isset($this->type) && $this->type !== null) {
            $uri .= '/' .$this->type;
        }

        if (isset($this->id) && $this->id !== null) {
            $uri .= '/' .$this->id;
        }

        return $uri;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

}
