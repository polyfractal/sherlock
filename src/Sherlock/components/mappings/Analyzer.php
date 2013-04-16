<?php
/**
 * User: zach
 * Date: 4/16/13
 * Time: 8:41 AM
 */
namespace Sherlock\components\mappings;

use Analog\Analog;
use Sherlock\components;
use Sherlock\common\exceptions;

/**
 * @method \Sherlock\components\mappings\Analyzer path() path(\string $value)
 * @method \Sherlock\components\mappings\Analyzer index() index(\bool $value)
 *
 */
class Analyzer extends \Sherlock\components\BaseComponent implements \Sherlock\components\MappingInterface
{
    protected $type;

    /**
     * @param null $type
     * @param null $hashMap
     */
    public function __construct($type = null, $hashMap = null)
    {
        //if $type is set, we need to wrap the mapping property in a type
        //this is used for multi-mappings on index creation
        if (isset($type)) {
            $this->type = $type;
        }

        $this->params['path'] = null;
        $this->params['index'] = null;

        parent::__construct($hashMap);
    }

    /**
     * @return array
     * @throws \Sherlock\common\exceptions\RuntimeException
     */
    public function toArray()
    {
        $ret = array();

        if (!isset($this->params['path'])) {
            Analog::error("Path must be set for Analyzer mapping");
            throw new exceptions\RuntimeException("Path must be set for Analyzer mapping");
        }

        $ret['_analyzer']['path'] = $this->params['path'];

        if (isset($this->params['index'])) {
            $ret['_analyzer']['index'] = $this->params['index'];
        }

        return $ret;

    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

}
