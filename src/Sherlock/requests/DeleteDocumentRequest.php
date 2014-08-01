<?php

namespace Sherlock\requests;


use Sherlock\common\exceptions;
use Sherlock\responses\DeleteResponse;

/**
 * This class facilitates deleting single documents into an ElasticSearch index
 *
 */
class DeleteDocumentRequest
{
    /**
     * @var \Elasticsearch\Client
     */
    protected $esClient;

    /**
     * @var array
     */
    protected $params;


    /**
     * @param  \Elasticsearch\Client $esClient
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @internal param $node
     */
    public function __construct($esClient)
    {
        $this->esClient       = $esClient;
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
//        $this->params['index'] = $index;
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
//        $this->params['type'] = $type;
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
        $this->params['id'] = $id;
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
        $id = $this->params['id'];

        if (isset($this->params['index'])) {
            $index = implode(',', $this->params['index']);
        } else {
            $index = '';
        }

        if (isset($this->params['type'])) {
            $type = implode(',', $this->params['type']);
        } else {
            $type = '';
        }

        $params = array(
            "index" => $index,
            "id" => $id,
            "type" => $type,
        );
        return $this->esClient->delete($this->params);
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
