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
 *
 * @method \Sherlock\requests\Command action() action(\string $value)
 * @method \Sherlock\requests\Command id() id(\string $value)
 * @method \Sherlock\requests\Command index() index(\string $value)
 * @method \Sherlock\requests\Command type() type(\string $value)
 * @method \Sherlock\requests\Command suffix() suffix(\string $value)
 */
class Command implements CommandInterface
{

    private $params;

    /**
     * @param array $hashMap Optional hashmap parameter, accepts an associative array to set parameters manually
     */
    public function __construct($hashMap = null)
    {
        if (is_array(($hashMap)) && count($hashMap) > 0) {
            //merge the provided values with our param array, overwriting defaults where necessary
            $this->params = array_merge($this->params, $hashMap);
        }

        $this->params['index'] = null;
        $this->params['action'] = null;
        $this->params['id'] = null;
        $this->params['type'] = null;
        $this->params['data'] = null;
        $this->params['suffix'] = null;

    }

    /**
     * @param $name
     * @param $args
     * @return \Sherlock\requests\Command
     */
    public function __call($name, $args)
    {
        $this->params[$name] = $args[0];

        return $this;
    }

    /**
     * @param \string|\array $data
     */
    public function data($data)
    {
        if (is_string($data)) {
            $this->params['data'] = json_decode($data);
        } elseif (is_array($data)) {
            $this->params['data'] = $data;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getURI()
    {
        $uri = '/'.$this->params['index'];

        foreach (array('type', 'id', 'suffix') as $item) {
            if (isset($this->params[$item]) && $this->params[$item] !== null) {
                $uri .= '/' .$this->params[$item];
            }
        }

        return $uri;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->params['action'];
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->params['data'];
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->params['index'];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->params['type'];
    }

}
