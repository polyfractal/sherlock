<?php
/**
 * User: Zachary Tong
 * Date: 2/12/13
 * Time: 7:37 PM
 */

namespace sherlock\requests;

use sherlock\components\queries;
use sherlock\common\exceptions;


class IndexRequest extends Request
{

	/**
	 * @var array
	 */
	protected $params;

	public function __construct($node, $index)
	{
		if (!isset($node))
			throw new \sherlock\common\exceptions\BadMethodCallException("Node argument required for IndexRequest");
		if (!isset($index))
			throw new \sherlock\common\exceptions\BadMethodCallException("Index argument required for IndexRequest");

		if (!is_array($node))
			throw new \sherlock\common\exceptions\BadMethodCallException("First parameter must be an node array");

		if(!is_array($index))
			$this->params['index'][] = $index;
		else
			$this->params['index'] = $index;

		parent::__construct($node);
	}

	public function __call($name, $args)
	{
		$this->params[$name] = $args[0];
		return $this;
	}


	/**
	 * @param string $index indices to operate on
	 * @param string $index,... indices to operate on
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

	public function delete()
	{
		\Analog\Analog::log("IndexRequest->execute() - ".print_r($this->params, true), \Analog\Analog::DEBUG);

		if (!isset($this->params['index']))
			throw new \sherlock\common\exceptions\RuntimeException("Index cannot be empty.");

		$index = implode(',', $this->params['index']);

		$uri = 'http://'.$this->node['host'].':'.$this->node['port'].'/'.$index;

		//required since PHP doesn't allow argument differences between
		//parent and children under Strict
		$this->_uri = $uri;
		$this->_data = null;
		$this->_action = 'delete';

		$ret =  parent::execute();
		print_r($ret);
	}

	public function mappings()
	{

	}

	public function settings()
	{

	}


	public function create()
	{
		\Analog\Analog::log("IndexRequest->execute() - ".print_r($this->params, true), \Analog\Analog::DEBUG);

		if (!isset($this->params['index']))
			throw new \sherlock\common\exceptions\RuntimeException("Index cannot be empty.");

		$index = implode(',', $this->params['index']);

		$uri = 'http://'.$this->node['host'].':'.$this->node['port'].'/'.$index;

		//required since PHP doesn't allow argument differences between
		//parent and children under Strict
		$this->_uri = $uri;
		$this->_data = null;


		$this->_action = 'put';

		$ret =  parent::execute();
		print_r($ret);
	}
}
