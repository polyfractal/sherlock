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
     * @param $commands
     */
    public function __construct($commands = null)
    {
        if ($commands !== null) {
            $this->commands = $commands;
        }

    }

    /**
     * @param \Sherlock\requests\Command $command
     */
    public function addCommand($command)
    {
        $this->commands[] = $command;
    }

    /**
     *
     */
    public function clearCommands()
    {
        $this->commands = array();
    }

    /**
     * Fill all commands that don't have an index set
     *
     * @param $index
     */
    public function fillIndex($index)
    {
        /** @param Command $value */
        $map = function ($value) use ($index) {
            $value->index = $index;
        };

        array_map($map, $this->commands);
    }

    /**
     * Fill all commands that don't have a type set
     *
     * @param $type
     */
    public function fillType($type)
    {

        /** @param Command $value */
        $map = function ($value) use ($type) {
            $value->type = $type;
        };

        array_map($map, $this->commands);
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