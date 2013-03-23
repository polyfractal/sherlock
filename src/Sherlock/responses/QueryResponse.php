<?php
/**
 * User: Zachary Tong
 * Date: 2/11/13
 * Time: 7:27 PM
 */

namespace Sherlock\responses;

class QueryResponse extends Response implements \IteratorAggregate, \Countable
{
    /**
     * @var int
     */
    public $took;

    /**
     * @var bool
     */
    public $timed_out;

    /**
     * @var int
     */
    public $total;

    /**
     * @var float
     */
    public $max_score;

    /**
     * @var array
     */
    public $hits;

    /**
     * @param  \Sherlock\common\tmp\RollingCurl\Request           $response
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     */
    public function __construct($response)
    {
        parent::__construct($response);

        $this->took = $this->responseData['took'];
        $this->timed_out = ($this->responseData['timed_out'] == '') ? false : true;

        if (isset($this->responseData['hits']['total']))
            $this->total = $this->responseData['hits']['total'];

        if (isset($this->responseData['hits']['max_score']))
            $this->max_score = $this->responseData['hits']['max_score'];

        if (isset($this->responseData['hits']['hits'])) {
            $this->hits = $this->responseData['hits']['hits'];

            //get rid of the underscores
            foreach ($this->hits as $hitKey => $hit) {
                foreach ($hit as $key => $value) {
                    if (substr($key,0,1)=='_') {
                        $this->hits[$hitKey][ltrim($key, '_')] = $value;
                        unset($this->hits[$hitKey][$key]);
                    }
                }
            }

        }

    }

    public function getIterator()
    {
        return new \ArrayIterator($this->hits);
    }

    public function count()
    {
        return sizeof($this->hits);
    }

}
