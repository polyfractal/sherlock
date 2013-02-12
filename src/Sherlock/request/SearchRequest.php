<?php
/**
 * User: Zachary Tong
 * Date: 2/10/13
 * Time: 12:10 PM
 */
namespace sherlock\Request;

use sherlock\components\queries;
use sherlock\common\exceptions;
use Guzzle\Http\Client;

/**
 * @method \sherlock\request\SearchRequest timeout() timeout(int $value)
 * @method \sherlock\request\SearchRequest from() from(int $value)
 * @method \sherlock\request\SearchRequest to() to(int $value)
 * @method \sherlock\request\SearchRequest search_type() search_type(int $value)
 * @method \sherlock\request\SearchRequest routing() routing(mixed $value)
 */
class SearchRequest extends Request
{

	protected $params;

	public function __construct($node)
	{

		parent::__construct($node);
	}
	public function __call($name, $args)
	{
		$this->params[$name] = $args[0];
		return $this;
	}


	/**
	 * @param string $index indices to query
	 * @param string $index,... indices to query
	 * @return SearchRequest
	 */
	public function index($index)
	{
		$this->params['index'] = array();
		$args = func_get_args();
		foreach($args as $arg)
		{
			$this->params['index'][] = $arg;
		}
		return $this;
	}

	/**
	 * @param string $type types to query
	 * @param string $type,... types to query
	 * @return SearchRequest
	 */
	public function type($type)
	{
		$this->params['type'] = array();
		$args = func_get_args();
		foreach($args as $arg)
		{
			$this->params['type'][] = $arg;
		}
		return $this;
	}

	/**
	 * @param \sherlock\components\BaseComponent $value Queries, filters, facets, etc to add to the query
	 * @param \sherlock\components\BaseComponent $value,... Queries, filters, facets, etc to add to the query
	 * @return SearchRequest
	 */
	public function query($value)
	{
		$this->params['query'] = array();
		$args = func_get_args();
		foreach($args as $arg)
		{
			$this->params['query'][] = $arg;
		}
		return $this;
	}




	public function execute()
	{
		\Analog\Analog::log("SearchRequest->execute() - ".print_r($this->params, true), \Analog\Analog::DEBUG);

		$finalQuery = array();

		if (count($this->params['query']) == 0)
			throw new \sherlock\common\exceptions\RuntimeException("Search query cannot be empty.");

		foreach($this->params['query'] as $query)
		{
			if ($query instanceof \sherlock\components\QueryInterface)
				$finalQuery[] = '"query" : '.(string)$query;
			elseif ($query instanceof \sherlock\components\FilterInterface)
				$finalQuery[] = '"filter" : '.$query;
		}

		if (isset($this->params['from']))
			$finalQuery[] = '"from" : "'.$this->params['from'].'"';

		if (isset($this->params['to']))
			$finalQuery[]=  '"to" : "'.$this->params['to'];

		if (isset($this->params['timeout']))
			$finalQuery[] =  '"timeout" : "'.$this->params['timeout'];

		$finalQuery = '{'.implode(',', $finalQuery).'}';

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

		if (isset($queryParams))
		{
			$queryParams = '?' . implode("&", $queryParams);
		}
		else
			$queryParams = '';

		print_r($this->node);


		$uri = 'http://'.$this->node['host'].':'.$this->node['port'].'/'.$index.'/'.$type.'/_search'.$queryParams;

		//required since PHP doesn't allow argument differences between
		//parent and children under Strict
		$this->_uri = $uri;
		$this->_data = $finalQuery;
		parent::execute();
	}
}



