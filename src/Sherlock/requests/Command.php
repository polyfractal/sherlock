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
 * @method \Sherlock\requests\Command data() data(\string $value)
 * @method \Sherlock\requests\Command id() id(\string $value)
 * @method \Sherlock\requests\Command index() index(\string $value)
 * @method \Sherlock\requests\Command type() type(\string $value)
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

    }
    
    /**
     * @param $name
     * @param $args
     * @return IndexDocumentRequest
     */
    public function __call($name, $args)
    {
        $this->params[$name] = $args[0];

        return $this;
    }
    
    
    /**
     * @return string
     */
    public function getURI()
    {
        $uri = '/'.$this->params['index'];

        if (isset($this->params['type']) && $this->params['type'] !== null) {
            $uri .= '/' .$this->params['type'];
        }

        if (isset($this->params['id']) && $this->params['id'] !== null) {
            $uri .= '/' .$this->params['id'];
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
