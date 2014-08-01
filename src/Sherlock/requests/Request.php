<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 8:54 AM
 * @package Sherlock\requests
 */

namespace Sherlock\requests;

use Sherlock\common\events\Events;
use Sherlock\common\events\RequestEvent;
use Sherlock\common\exceptions;
use Sherlock\common\tmp\RollingCurl;
use Sherlock\responses\IndexResponse;

use Sherlock\responses\Response;

/**
 * Base class for various requests.
 *
 * Handles generic functionality such as transport.
 */
class Request
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
        if (!isset($esClient))
        {
            throw new \Sherlock\common\exceptions\BadMethodCallException("esClient argument required for Request");
        }
        $this->esClient = $esClient;
    }

    /**
     * Set the index to operate on
     *
     * @param  string       $index     indices to operate on
     * @param  string       $index,... indices to operate on
     *
     * @return IndexRequest
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
     * Set the type to operate on
     *
     * @param  string       $type     indices to operate on
     * @param  string       $type,... indices to operate on
     *
     * @return IndexRequest
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
}
