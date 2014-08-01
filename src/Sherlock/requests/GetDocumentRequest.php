<?php
/**
 * User: Jim Heys
 * Date: 6/17/13
 * Time: 5:06 PM
 * @package Sherlock\requests
 */
namespace Sherlock\requests;

use Sherlock\common\exceptions;
use Sherlock\components;
use Sherlock\components\queries;
use Symfony\Component\EventDispatcher\EventDispatcher;
use sherlock\components\FacetInterface;
use Sherlock\responses\DocumentResponse;

/**
 * SearchRequest facilitates document retrieval by id
 */
class GetDocumentRequest
{
    /**
     * @var \Elasticsearch\Client
     */
    protected $esClient;

    protected $params;


    /**
     * @param  \Elasticsearch\Client $esClient
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     */
    public function __construct($esClient)
    {
        $this->esClient       = $esClient;
    }

    /**
     * Sets the id of the document
     * @param $id
     * @return GetDocumentRequest
     */
    public function id($id)
    {
        $this->params['id'] = $id;

        return $this;
    }

    /**
     * Sets the index to operate on
     *
     * @param  string        $index     indices to query
     * @param  string        $index,... indices to query
     *
     * @return GetDocumentRequest
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
     * Sets the type to operate on
     *
     * @param  string        $type     types to query
     * @param  string        $type,... types to query
     *
     * @return GetDocumentRequest
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
     * Execute the search request on the ES cluster
     *
     * @throws \Sherlock\common\exceptions\RuntimeException
     * @return \Sherlock\responses\QueryResponse
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

        if (isset($queryParams)) {
            $queryParams = '?' . implode("&", $queryParams);
        } else {
            $queryParams = '';
        }

        $params = array(
            "index" => $index,
            "id" => $id,
            "type" => $type,
        );
        $ret= $this->esClient->get($params);
        return $ret;
    }

    /**
     * @param $response
     * @return \Sherlock\responses\DocumentResponse|\Sherlock\responses\Response
     */
    protected function getReturnResponse($response)
    {
        return new DocumentResponse($response);
    }
}
