<?php
/**
 * User: Zachary Tong
 * Date: 2/16/13
 * Time: 8:12 PM
 */

namespace Sherlock\wrappers;

use Sherlock\common\exceptions;

/**
 * @method \Sherlock\components\mappings\String String() String()
 * @method \Sherlock\components\mappings\Number Number() Number()
 * @method \Sherlock\components\mappings\Date Date() Date()
 * @method \Sherlock\components\mappings\Boolean Boolean() Boolean()
 * @method \Sherlock\components\mappings\Binary Binary() Binary()
 * @method \Sherlock\components\mappings\Object Object() Object()
 * @method \Sherlock\components\mappings\Analyzer Analyzer() Analyzer()
 */
class MappingPropertyWrapper
{
    /**
     * @var \Sherlock\components\MappingInterface
     */
    protected $property;
       protected $type;

    /**
     * @param  string                                             $type
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     */
    public function __construct($type)
    {
        if (!isset($type)) {
            \Analog\Analog::log("Type must be set for mapping property.", \Analog\Analog::ERROR);
            throw new \Sherlock\common\exceptions\BadMethodCallException("Type must be set for mapping property");
        }

        $this->type = $type;

    }
    public function __call($name, $arguments)
    {
        $class = '\\Sherlock\\components\\mappings\\'.$name;

        //Type can be passed in the with constructor, used for multi-mappings on index creation
        //Argument[0] is an optional hashmap to define properties via an array
        if (count($arguments) > 0)
            $this->property =  new $class($this->type, $arguments[0]);
        else
            $this->property =  new $class($this->type);

        return $this->property;
    }

}
