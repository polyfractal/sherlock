<?php
/**
 * User: Zachary Tong
 * Date: 2/10/13
 * Time: 12:10 PM
 * @package Sherlock\requests
 */
namespace Sherlock\requests;

use Analog\Analog;
use Sherlock\common\exceptions;
use Symfony\Component\EventDispatcher\EventDispatcher;
use sherlock\components\FacetInterface;

/**
 * SearchRequest facilitates searching an ES index using the ES query DSL
 *
 * @method \Sherlock\requests\SearchRequest timeout() timeout(\int $value)
 * @method \Sherlock\requests\SearchRequest from() from(\int $value)
 * @method \Sherlock\requests\SearchRequest size() size(\int $value)
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
	 * @var \Symfony\Component\EventDispatcher\EventDispatcher
	 */
	protected $dispatcher;



	/**
	 * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
	 * @throws \Sherlock\common\exceptions\BadMethodCallException
	 */
	public function __construct($dispatcher)
    {
		if (!isset($dispatcher))
			throw new \Sherlock\common\exceptions\BadMethodCallException("Dispatcher argument required for IndexRequest");

        $this->params['filter'] = array();
		$this->dispatcher = $dispatcher;

        parent::__construct($dispatcher);
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
	 * Sets the query that will be executed
	 *
	 * @param $query
	 * @return SearchRequest
	 */
    public function query($query)
    {
        $this->params['query'] = $query;

        return $this;
    }

    /**
     * Sets the query or queries that will be executed
     *
     * @param  \Sherlock\components\SortInterface|array,... $value
     * @return SearchRequest
     */
    public function sort($value)
    {
		$args = func_get_args();
		Analog::debug("SearchRequest->sort(".print_r($args, true).")");

		//single param, array of sorts
		if (count($args) == 1 && is_array($args[0]))
			$args = $args[0];

		foreach ($args as $arg) {
			if ($arg instanceof \Sherlock\components\SortInterface)
				$this->params['sort'][] = $arg->toArray();
		}

		return $this;
    }

	/**
	 * Sets the filter that will be executed
	 *
	 * @param $filter
	 * @return SearchRequest
	 */
    public function filter($filter)
    {
        $this->params['filter'] = $filter;

        return $this;
    }


	/**
	 * Sets the facets to operate on
	 *
	 * @param  \Sherlock\components\FacetInterface        $facets     types to query
	 * @param  \Sherlock\components\FacetInterface        $facets,... types to query
	 * @return SearchRequest
	 */
	public function facets($facets)
	{
		$this->params['facets'] = array();
		$args = func_get_args();
		foreach ($args as $arg) {
			if ($arg instanceof FacetInterface)
				$this->params['facets'][] = $arg;
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


        $uri = '/'.$index.'/'.$type.'/_search'.$queryParams;

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

		// If the query is a raw one, use query as-is
		if($this->params['query'] instanceof \Sherlock\components\queries\Raw)
			return $this->params['query']->toJSON();

        if (isset($this->params['query']) && $this->params['query'] instanceof \Sherlock\components\QueryInterface)
        	$finalQuery['query'] = $this->params['query']->toArray();

		if (isset($this->params['filter']) && $this->params['filter'] instanceof \Sherlock\components\FilterInterface)
			$finalQuery['filter'] = $this->params['filter']->toArray();

		if (isset($this->params['facets']))
		{
			$tFacets = array();
			foreach($this->params['facets'] as $facet)
			{
				//@todo Investigate a better way of doing this
				//array_merge is supposedly slow when merging arrays of arrays
				if ($facet instanceof \Sherlock\components\FacetInterface)
					$tFacets = array_merge($tFacets, $facet->toArray());
			}
			$finalQuery['facets'] = $tFacets;
			unset($tFacets);
		}


        if (isset($this->params['from']))
            $finalQuery['from'] = $this->params['from'];

        if (isset($this->params['size']))
            $finalQuery['size']=  $this->params['size'];

        if (isset($this->params['timeout']))
            $finalQuery['timeout'] =  $this->params['timeout'];

		if (isset($this->params['sort']))
			$finalQuery['sort'] =  $this->params['sort'];



        $finalQuery = json_encode($finalQuery, true);

        return $finalQuery;
    }

}
