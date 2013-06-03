<?php

namespace Sherlock\facades;

/**
 * Class HighlightFacade
 * @package Sherlock\Facades
 *
 *
 * @method \Sherlock\components\highlights\Highlight Highlight() Highlight()
 */
class HighlightFacade
{
    /**
     * @var \Sherlock\components\HighlightInterface
     */
    protected $highlight;


    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $class = '\\Sherlock\\components\\highlights\\' . $name;

        if (count($arguments) > 0) {
            $this->highlight = new $class($arguments[0]);
        } else {
            $this->highlight = new $class();
        }

        return $this->highlight;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->highlight;
    }

}
