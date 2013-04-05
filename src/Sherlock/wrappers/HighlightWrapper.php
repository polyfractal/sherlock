<?php

namespace Sherlock\wrappers;

/**
 * Class HighlightWrapper
 * @package Sherlock\wrappers
 *
 *
 * @method \Sherlock\components\highlights\Highlight Highlight() Highlight()
 */
class HighlightWrapper
{
    /**
     * @var \Sherlock\components\HighlightInterface
     */
    protected $highlight;

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $class = '\\Sherlock\\components\\highlights\\'.$name;

        if (count($arguments) > 0) {
            $this->highlight =  new $class($arguments[0]);
        } else {
            $this->highlight =  new $class();
        }

        return $this->highlight;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->highlight;
    }

}
