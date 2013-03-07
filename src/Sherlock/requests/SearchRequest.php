<?php
/**
 * User: Zachary Tong
 * Date: 2/10/13
 * Time: 12:10 PM
 * @package Sherlock\requests
 */
namespace Sherlock\requests;

use Sherlock\common\exceptions;

/**
 * SearchRequest facilitates searching an ES index using the ES query DSL
 *
 * @method \Sherlock\requests\SearchRequest timeout() timeout(\int $value)
 * @method \Sherlock\requests\SearchRequest from() from(\int $value)
 * @method \Sherlock\requests\SearchRequest to() to(\int $value)
 * @method \Sherlock\requests\SearchRequest search_type() search_type(\int $value)
 * @method \Sherlock\requests\SearchRequest routing() routing(mixed $value)
 */
class SearchRequest extends Request
{
	/**
	 * @var array
	 */
	protected $params;

	/**
	 * @param $node
	 */
	public function __construct($node)
    {
        $this->params['filter'] = array();
        parent::__construct($node);
    }

	/**
	 * @param $name
	 * @param $args
	 * @return SearchRequest
	 */
	public function __call($name, $args)
    {
        $this->params[$name] = $args[0];

        return $this;
    }

    /**
	 * Sets the index to operate on
	 *
     * @param  string        $index     indices to query
     * @param  string        $index,... indices to query
     * @return SearchRequest
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
	 * Sets the type to operate on
	 *
     * @param  string        $type     types to query
     * @param  string        $type,... types to query
     * @return SearchRequest
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
	 * Sets the query or queries that will be executed
	 *
     * @param  \Sherlock\components\BaseComponent $value
     * @param  \Sherlock\components\BaseComponent $value,...
     * @return SearchRequest
     */
    public function query($value)
    {
        $this->params['query'] = array();
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->params['query'][] = $arg;
        }

        return $this;
    }

    /**
     * Sets the query or queries that will be executed
     *
     * @param  \Sherlock\components\SortComponent $value
     * @return SearchRequest
     */
    public function sort($value)
    {
        $this->params['sort'] = $value;
        return $this;
    }

    /**
	 * Sets the filter or filters that will be executed
	 *
     * @param  \Sherlock\components\BaseComponent $value
     * @param  \Sherlock\components\BaseComponent $value,...
     * @return SearchRequest
     */
    public function filter($value)
    {
        $this->params['filter'] = array();
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->params['filter'][] = $arg;
        }

        return $this;
    }

    /**
	 * Execute the search request on the ES cluster
	 *
     * @return \Sherlock\responses\QueryResponse
     */
    public function execute()
    {
        \Analog\Analog::log("SearchRequest->execute() - ".print_r($this->params, true), \Analog\Analog::DEBUG);

        $finalQuery = $this->composeFinalQuery();

        if (isset($this->params['index']))
            $index = implode(',', $this->params['index']);
        else
            $index = '';

        if (isset($this->params['type']))
            $type = implode(',', $this->params['type']);
        else
            $type = '';

        if (isset($this->params['search_type']))
            $queryParams[] = $this->params['search_type'];

        if (isset($this->params['routing']))
            $queryParams[] = $this->params['routing'];

        if (isset($queryParams)) {
            $queryParams = '?' . implode("&", $queryParams);
        } else
            $queryParams = '';

        $uri = 'http://'.$this->node['host'].':'.$this->node['port'].'/'.$index.'/'.$type.'/_search'.$queryParams;

        //required since PHP doesn't allow argument differences between
        //parent and children under Strict
        $this->_uri = $uri;
        $this->_data = $finalQuery;

        //Guzzle doesn't allow GET with request body, use post
        $this->_action = 'post';

        return parent::execute();
    }

	/**
	 * Return a JSON representation of the final search request
	 *
	 * @return string
	 */
	public function toJSON()
    {
        $finalQuery = $this->composeFinalQuery();

        return $finalQuery;
    }

	/**
	 * Composes the final query, aggregating together the queries, filters, facets and associated parameters
	 *
	 * @return string
	 * @throws \Sherlock\common\exceptions\RuntimeException
	 */
	private function composeFinalQuery()
    {
        $finalQuery = array();

        if (!isset($this->params['query']) || count($this->params['query']) == 0)
            throw new \Sherlock\common\exceptions\RuntimeException("Search query cannot be empty.");

        foreach ($this->params['query'] as $query) {
            if ($query instanceof \Sherlock\components\QueryInterface)
                $finalQuery[] = '"query":'.$query->toJSON();
        }

        foreach ($this->params['filter'] as $query) {
            if ($query instanceof \Sherlock\components\FilterInterface)
                $finalQuery[] = '"filter":'.$query->toJSON();
        }

        if (isset($this->params['from']))
            $finalQuery[] = '"from":"'.$this->params['from'].'"';

        if (isset($this->params['to']))
            $finalQuery[]=  '"to":"'.$this->params['to'];

        if (isset($this->params['timeout']))
            $finalQuery[] =  '"timeout":"'.$this->params['timeout'];

        if (isset($this->params['sort'])) {
            $sort = $this->params['sort'];
            if ($sort instanceof \Sherlock\components\SortComponent)
                $finalQuery[] = '"sort":'.$sort->toJSON();
        }

        $finalQuery = '{'.implode(',', $finalQuery).'}';

        return $finalQuery;
    }

}
