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
 * @property \sherlock\components\QueryInterface $query
 */
class SearchRequest extends Request
{
	protected $query;
	protected $params;

	public function index($index)
	{
		$this->params['indices'] = array($index);
		return $this;
	}

	public function __get($var)
	{
		return $this->query;
	}

	public function __set($var, $value)
	{
		$this->query = $value;
	}

	/**
	 * @return void
	 * @internal param string $index indices to query
	 */
	public function indices()
	{
		$this->params['indices'] = array();
		$args = func_get_args();
		foreach($args as $arg)
		{
			$this->params['indices'][] = $arg;
		}
	}

	/**
	 * @return QueryWrapper
	 */
	public function query()
	{
		$this->query = new QueryWrapper();
		return $this->query();
	}



	public function execute()
	{
		$queryString = (string)$this->query;


		//parent::execute();
	}
}



