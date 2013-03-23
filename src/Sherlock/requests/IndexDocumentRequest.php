<?php
/**
 * User: Zachary Tong
 * Date: 2/17/13
 * Time: 6:39 PM
 * @package Sherlock\requests
 */

namespace Sherlock\requests;

use Analog\Analog;
use Guzzle\Batch\Batch;
use Sherlock\common\exceptions;

/**
 * This class facilitates indexing single documents into an ElasticSearch index
 *
 */
class IndexDocumentRequest extends Request
{
    protected $dispatcher;

    /**
     * @var array
     */
    protected $params;


    /**
     * @param  \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @internal param $node
     */
    public function __construct($dispatcher)
    {
        if (!isset($dispatcher))
            throw new \Sherlock\common\exceptions\BadMethodCallException("Dispatcher argument required for IndexRequest");

        $this->dispatcher = $dispatcher;

        parent::__construct($dispatcher);
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
     * Set the index to add documents to
     *
     * @param  string               $index     indices to query
     * @param  string               $index,... indices to query
     * @return IndexDocumentRequest
     */
    public function index($index)
    {
        $this->params['index'] = array();
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->params['index'][] = $arg;
        }

        return $this;
    }

    /**
     * Set the type to add documents to
     *
     * @param  string               $type
     * @param  string               $type,...
     * @return IndexDocumentRequest
     */
    public function type($type)
    {
        $this->params['type'] = array();
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->params['type'][] = $arg;
        }

        return $this;
    }

    /**
     * The document to index
     *
     * @param  \string|\array $value
     * @param null $id
     * @throws \Sherlock\common\exceptions\RuntimeException
     * @return IndexDocumentRequest
     */
    public function document($value, $id = null)
    {
        if (! $this->batch instanceof BatchCommand) {
            Analog::error("Cannot add a new document to an external BatchCommandInterface");
            throw new exceptions\RuntimeException("Cannot add a new document to an external BatchCommandInterface");
        }

        $command = new Command();
        if (is_array($value)) {
            $command->data = json_encode($value, true);

        } elseif (is_string($value)) {
            $command->data = $value;
        }

        if ($id !== null) {
            $command->id = $id;
            $command->action = 'put';
        } else {
            $command->action = 'post';
        }

        //Only doing this because typehinting is wonky without it...
        if ($this->batch instanceof BatchCommand) {
            $this->batch->addCommand($command);
        }

        return $this;
    }

    /**
     * Accepts an array of Commands or a BatchCommand
     * @param array|BatchCommand $values
     * @return $this
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     */
    public function documents($values)
    {
        if ($values instanceof BatchCommandInterface) {
            $this->batch = $values;
        } elseif (is_array($values)) {

            $isBatch = true;
            $batch = new BatchCommand();

            /**
             * @param mixed $value
             */
            $map = function ($value) use ($isBatch, $batch) {
                if (!$value instanceof Command) {
                    $isBatch = false;
                } else {
                    $batch->addCommand($value);
                }
            };

            array_map($map, $values);

            if (!$isBatch) {
                Analog::error("If an array is supplied, all elements must be a Command object.");
                throw new exceptions\BadMethodCallException("If an array is supplied, all elements must be a Command object.");
            }

            $this->batch = $batch;

        } else {
            Analog::error("Documents method only accepts arrays of Commands or BatchCommandInterface objects");
            throw new exceptions\BadMethodCallException("Documents method only accepts arrays of Commands or BatchCommandInterface objects");
        }

        return $this;

    }

    /**
     * Perform the indexing operation
     *
     * @throws exceptions\RuntimeException
     * @return \Sherlock\responses\IndexResponse
     */
    public function execute()
    {
        Analog::debug("IndexDocumentRequest->execute() - ".print_r($this->params, true));

        foreach (array('index', 'type') as $key) {
            if (!isset($this->params[$key])) {
                Analog::error($key." cannot be empty.");
                throw new exceptions\RuntimeException($key." cannot be empty.");
            }
        }

        foreach (array('index', 'type') as $key) {
            if (count($this->params[$key]) > 1) {
                Analog::error("Only one ".$key." may be inserted into at a time.");
                throw new exceptions\RuntimeException("Only one ".$key." may be inserted into at a time.");
            }
        }

        //if this is an internal Sherlock BatchCommand, make sure index/types/action are filled
        if ($this->batch instanceof BatchCommand) {
            $this->batch->fillIndex($this->params['index'][0]);
            $this->batch->fillType($this->params['type'][0]);
        }

        return parent::execute();
    }

}
