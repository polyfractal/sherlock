<?php
/**
 * User: zach
 * Date: 3/22/13
 * Time: 7:20 AM
 */

namespace Sherlock\requests;

/**
 * Class BatchCommand
 * @package Sherlock\requests
 */
class BatchCommand implements BatchCommandInterface
{

    /**
     * @var
     */
    protected $commands = array();

    /**
     * @param null $commands
     */
    public function __construct($commands = null)
    {
        if ($commands !== null) {
            $this->commands = $commands;
        }

    }

    /**
     * @param \Sherlock\requests\Command $command
     * @return $this
     */
    public function addCommand($command)
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearCommands()
    {
        $this->commands = array();

        return $this;
    }

    /**
     * Fill all commands that don't have an index set
     *
     * @param $index
     * @return $this
     */
    public function fillIndex($index)
    {
        /** @param Command $value */
        $map = function ($value) use ($index) {
            if ($value->getIndex() === null) {
                $value->index($index);
            }
        };

        array_map($map, $this->commands);

        return $this;
    }

    /**
     * Fill all commands that don't have a type set
     *
     * @param $type
     * @return $this
     */
    public function fillType($type)
    {

        /** @param Command $value */
        $map = function ($value) use ($type) {
            if ($value->getType() === null) {
                $value->type($type);
            }
        };

        array_map($map, $this->commands);

        return $this;
    }

    /**
     *
     */
    public function rewind()
    {
        reset($this->commands);
    }

    /**
     * @return Command
     */
    public function current()
    {
        return current($this->commands);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->commands);
    }

    /**
     * @return Command|void
     */
    public function next()
    {
        return next($this->commands);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return false !== current($this->commands);
    }

}
