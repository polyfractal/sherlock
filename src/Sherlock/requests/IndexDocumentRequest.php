<?php
/**
 * User: Zachary Tong
 * Date: 2/17/13
 * Time: 6:39 PM
 * @package Sherlock\requests
 */

namespace Sherlock\requests;


use Sherlock\common\exceptions;
use Sherlock\responses\IndexResponse;

/**
 * This class facilitates indexing single documents into an ElasticSearch index
 * @todo Refactor this whole damn thing
 *
 */
class IndexDocumentRequest extends Request
{

    /** @var Command */
    private $currentCommand;


    /**
     * @param  \Elasticsearch\Client $esClient
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @internal param $node
     */
    public function __construct($esClient)
    {

        $this->params['index'] = array();
        $this->params['type']  = array();

        $this->currentCommand   = null;
        $this->params['update'] = false;

        $this->params['updateScript'] = null;
        $this->params['updateParams'] = null;
        $this->params['updateUpsert'] = null;
        $this->params['doc']          = null;

        parent::__construct($esClient);
    }


    /**
     * @param $name
     * @param $args
     *
     * @return IndexDocumentRequest
     */
    public function __call($name, $args)
    {
        $this->params[$name] = $args[0];

        return $this;
    }



//    /**
//     * @param \string $script
//     *
//     * @return $this
//     */
//    public function updateScript($script)
//    {
//        $this->params['updateScript'] = $script;
//        $this->currentCommandCheck();
//        return $this;
//    }
//
//
//    /**
//     * @param \array $params
//     *
//     * @return $this
//     */
//    public function updateParams($params)
//    {
//        $this->params['updateParams'] = $params;
//        $this->currentCommandCheck();
//        return $this;
//    }
//
//
//    /**
//     * @param \string $upsert
//     *
//     * @return $this
//     */
//    public function updateUpsert($upsert)
//    {
//        $this->params['updateUpsert'] = $upsert;
//        $this->currentCommandCheck();
//        return $this;
//    }


    /**
     * The document to index
     *
     * @param  \string|\array $value
     * @param  null           $id
     * @param bool|null       $update
     *
     * @throws \Sherlock\common\exceptions\RuntimeException
     * @return IndexDocumentRequest
     */
    public function document($value, $id = null, $update = false)
    {
//        if (!$this->batch instanceof BatchCommand) {
//                        throw new exceptions\RuntimeException("Cannot add a new document to an external BatchCommandInterface");
//        }
//
//        $this->finalizeCurrentCommand();
//
//
//        if (is_array($value)) {
//            $this->params['doc'] = $value;
//
//        } elseif (is_string($value)) {
//            $this->params['doc'] = json_decode($value, true);
//        }
//
//        if ($id !== null) {
//            $this->currentCommand->id($id)
//                ->action('put');
//
//            $this->params['update'] = $update;
//
//        } else {
//            $this->currentCommand->action('post');
//
//            $this->params['update'] = false;
//        }
        if($update){
            $this->params['body']['doc'] = $value;
        } else {
            $this->params['body'] = $value;
        }

        $this->params['id'] = $id;
        $this->params['update'] = $update;
        //print_r($this->params);
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
     * Perform the indexing operation
     *
     * @throws exceptions\RuntimeException
     * @return \Sherlock\responses\IndexResponse
     */
    public function execute()
    {

        /*
        foreach (array('index', 'type') as $key) {
            if (!isset($this->params[$key])) {
                                throw new exceptions\RuntimeException($key." cannot be empty.");
            }
        }

        foreach (array('index', 'type') as $key) {
            if (count($this->params[$key]) > 1) {
                                throw new exceptions\RuntimeException("Only one ".$key." may be inserted into at a time.");
            }
        }

        */




        $params['index']=$this->params['index'];
        $params['type']=$this->params['type'];
        $params['id']=$this->params['id'];
        $params['body']=$this->params['body'];
        //print_r($this->params);

        return $this->esClient->index($params);

    }



    /**
     *This is all horribly terible and will be ripped out as soon as possible.
     *
     * Basically, This class stores an intermediate Command that maintains state across
     * chained requests, which allows updates (scripts, params, etc) to affect it.
     *
     * The command is finalized when a new document is added, or the request is executed.
     * Finalization means collapsing the doc field data into a param, as well as updating
     * the action/suffix as necessary.
     */
    private function finalizeCurrentCommand()
    {

        if ($this->batch instanceof BatchCommand && $this->currentCommand !== null) {

            if (isset($this->params['update']) && $this->params['update'] === true) {
                $this->currentCommand->action('post')->suffix('_update');


                if ($this->params['doc'] !== null) {
                    $data["doc"] = $this->params['doc'];
                }

                if ($this->params['updateScript'] !== null) {
                    $data["script"] = $this->params['updateScript'];
                }

                if ($this->params['updateParams'] !== null) {
                    $data["params"] = $this->params['updateParams'];
                }

                if ($this->params['updateUpsert'] !== null) {
                    $data["upsert"] = $this->params['updateUpsert'];
                }

                $this->currentCommand->data($data);

            } else {
                $this->currentCommand->data($this->params['doc']);
            }

            $this->batch->addCommand($this->currentCommand);

            $this->params['update'] = false;
        }

        $this->currentCommand = new Command();
    }


    /**
     *Simple helper function to check if the current command is populated
     * If not, create a new one
     */
    private function currentCommandCheck()
    {
        $this->params['update'] = true;
        if ($this->currentCommand === null) {
            $this->currentCommand = new Command();
        }

    }

    /**
     * @param $response
     * @return \Sherlock\responses\IndexResponse|\Sherlock\responses\Response
     */
    protected function getReturnResponse($response)
    {
        return new IndexResponse($response);
    }

}
