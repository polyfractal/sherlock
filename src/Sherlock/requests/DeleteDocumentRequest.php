<?php

namespace Sherlock\requests;


use Sherlock\common\exceptions;
use Sherlock\responses\DeleteResponse;

/**
 * This class facilitates deleting single documents into an ElasticSearch index
 *
 */
class DeleteDocumentRequest extends Request
{
    protected $dispatcher;

    /**
     * @var array
     */
    protected $params;


    /**
     * @param  \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @internal param $node
     */
    public function __construct($dispatcher)
    {
        if (!isset($dispatcher)) {
            throw new \Sherlock\common\exceptions\BadMethodCallException("Dispatcher argument required for IndexRequest");
        }

        $this->dispatcher = $dispatcher;

        parent::__construct($dispatcher);
    }


    /**
     * @param $name
     * @param $args
     *
     * @return DeleteDocumentRequest
     */
    public function __call($name, $args)
    {
        $this->params[$name] = $args[0];

        return $this;
    }


    /**
     * Set the index to delete documents from
     *
     * @param  string               $index     indices to query
     * @param  string               $index,... indices to query
     *
     * @return DeleteDocumentRequest
     */
    public function index($index)
    {
        $this->params['index'] = array();
        $args                  = func_get_args();
        foreach ($args as $arg) {
            $this->params['index'][] = $arg;
        }

        return $this;
    }


    /**
     * Set the type to delete documents from
     *
     * @param  string               $type
     * @param  string               $type,...
     *
     * @return DeleteDocumentRequest
     */
    public function type($type)
    {
        $this->params['type'] = array();
        $args                 = func_get_args();
        foreach ($args as $arg) {
            $this->params['type'][] = $arg;
        }

        return $this;
    }


    /**
     * The document to delete
     *
     * @param  null                                         $id
     *
     * @throws \Sherlock\common\exceptions\RuntimeException
     * @return DeleteDocumentRequest
     */
    public function document($id)
    {
        if (!$this->batch instanceof BatchCommand) {
                        throw new exceptions\RuntimeException("Cannot delete a document from an external BatchCommandInterface");
        }

        $command = new Command();
        $command->id($id)
            ->action('delete');

        //Only doing this because typehinting is wonky without it...
        if ($this->batch instanceof BatchCommand) {
            $this->batch->addCommand($command);
        }

        return $this;
    }


    /**
     * Accepts an array of Commands or a BatchCommand
     *
     * @param array|BatchCommandInterface $values
     *
     * @return $this
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     */
    public function documents($values)
    {
        if ($values instanceof BatchCommandInterface) {
            $this->batch = $values;
        } elseif (is_array($values)) {

            $isBatch = true;
            $batch   = new BatchCommand();

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
                                throw new exceptions\BadMethodCallException("If an array is supplied, all elements must be a Command object.");
            }

            $this->batch = $batch;

        } else {
                        throw new exceptions\BadMethodCallException("Documents method only accepts arrays of Commands or BatchCommandInterface objects");
        }

        return $this;

    }


    /**
     * Perform the delete operation
     *
     * @throws exceptions\RuntimeException
     * @return \Sherlock\responses\DeleteResponse
     */
    public function execute()
    {

        //if this is an internal Sherlock BatchCommand, make sure index/types/action are filled
        if ($this->batch instanceof BatchCommand) {
            $this->batch->fillIndex($this->params['index'][0])
                ->fillType($this->params['type'][0]);
        }

        return parent::execute();
    }

    /**
     * @param $response
     * @return \Sherlock\responses\DeleteResponse|\Sherlock\responses\Response
     */
    protected function getReturnResponse($response)
    {
        return new DeleteResponse($response);
    }
}
