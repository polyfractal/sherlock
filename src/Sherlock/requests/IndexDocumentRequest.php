<?php
/**
 * User: Zachary Tong
 * Date: 2/17/13
 * Time: 6:39 PM
 */

namespace sherlock\requests;

use sherlock\components\queries;
use sherlock\common\exceptions;


/**
 * @method \sherlock\requests\SearchRequest id() id(\mixed $value)
 */
class IndexDocumentRequest extends Request
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
	 * @return IndexDocumentRequest
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
	 * @param string $type
	 * @param string $type,...
	 * @return IndexDocumentRequest
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
	 * @param \string|\array $value
	 * @return IndexDocumentRequest
	 */
	public function document($value)
	{
		if (is_array($value))
			$this->params['document'] = $value;
		elseif (is_string($value))
			$this->params['document'] = json_decode($value,true);

		return $this;
	}


	/**
	 * @throws \sherlock\common\exceptions\RuntimeException
	 * @return \sherlock\responses\IndexResponse
	 */
	public function execute()
	{
		\Analog\Analog::log("IndexDocumentRequest->execute() - ".print_r($this->params, true), \Analog\Analog::DEBUG);

		foreach(array('document', 'index', 'type') as $key)
		{
			if (!isset($this->params[$key]))
			{
				\Analog\Analog::log($key." cannot be empty.", \Analog\Analog::ERROR);
				throw new \sherlock\common\exceptions\RuntimeException($key." cannot be empty.");
			}
		}

		foreach(array('index', 'type') as $key)
		{
			if (count($this->params[$key]) > 1)
			{
				\Analog\Analog::log("Only one ".$key." may be inserted into at a time.", \Analog\Analog::ERROR);
				throw new \sherlock\common\exceptions\RuntimeException("Only one ".$key." may be inserted into at a time.");
			}
		}


		//If an id is supplied, this is a put with id, otherwise post without
		if (isset($this->params['id']))
		{
			$id = $this->params['id'];
			$this->_action = 'put';
		}
		else
		{
			$id = '';
			$this->_action = 'post';
		}


		$uri = 'http://'.$this->node['host'].':'.$this->node['port'].'/'.$this->params['index'][0].'/'.$this->params['type'][0].'/'.$id;

		//required since PHP doesn't allow argument differences between
		//parent and children under Strict
		$this->_uri = $uri;
		$this->_data = json_encode($this->params['document'], JSON_FORCE_OBJECT);

		return parent::execute();
	}






}



