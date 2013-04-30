<?php
/**
 * User: Zachary Tong
 * Date: 4/30/13
 * Time: 1:20 PM
 * @package Sherlock\requests
 */
namespace Sherlock\requests;

use Analog\Analog;
use Sherlock\common\exceptions;
use Sherlock\components;
use Sherlock\components\queries;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * RawRequest allows arbitrary requests on an ES index
 *
 * @method \Sherlock\requests\RawRequest uri() uri(\string $value)
 * @method \Sherlock\requests\RawRequest method() method(\int $value)
 */
class RawRequest extends Request
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @param  \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     * @throws exceptions\BadMethodCallException
     */
    public function __construct($dispatcher)
    {
        if (!isset($dispatcher)) {
            throw new exceptions\BadMethodCallException("Dispatcher argument required for RawRequest");
        }

        $this->dispatcher = $dispatcher;

        parent::__construct($dispatcher);
    }

    /**
     * @param $name
     * @param $args
     * @return RawRequest
     */
    public function __call($name, $args)
    {
        $this->params[$name] = $args[0];

        return $this;
    }

    /**
     * @param $body
     * @return $this
     */
    public function body($body)
    {
        if (is_array(($body)) && count($body) > 0) {
            //Raw array hash map provided
            //put it right into the params
            $this->params['body'] = $body;
        } elseif (is_string($body)) {
            //Raw JSON has been provided
            //Decode from JSON into array
            $this->params['body'] = json_decode($body, true);
        }
        return $this;
    }



    /**
     * Execute the RawRequest
     *
     * @throws \Sherlock\common\exceptions\RuntimeException
     * @return \Sherlock\responses\QueryResponse
     */
    public function execute()
    {
        Analog::debug("RawRequest->execute() - ".print_r($this->params, true));

        if (!isset($this->params['uri'])) {
            Analog::error("URI is required for RawRequest");
            throw new exceptions\RuntimeException("URI is required for RawRequest");
        }

        if (!isset($this->params['method'])) {
            Analog::error("Method is required for RawRequest");
            throw new exceptions\RuntimeException("Method is required for RawRequest");
        }

        $command = new Command();
        $command->index($this->params['uri'])
            ->action($this->params['method']);

        if (isset($this->params['body'])) {
            $command->data($this->params['body']);
        }


        $this->batch->clearCommands();
        $this->batch->addCommand($command);

        $ret =  parent::execute();

        return $ret[0];
    }

    /**
     * Return a JSON representation of the final search request
     *
     * @return string
     */
    public function toJSON()
    {
        $finalQuery = "";

        if (isset($this->params['body'])) {
            $finalQuery = json_encode($this->params['body']);
        }


        return $finalQuery;
    }
}
