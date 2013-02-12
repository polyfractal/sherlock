<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 8:54 AM
 */

namespace sherlock\requests;
use sherlock\common\exceptions;
use sherlock\responses\Response;
use Guzzle\Http\Client;

class Request
{
	protected $node;

	//required since PHP doesn't allow argument differences between
	//parent and children under Strict
	protected $_uri;
	protected $_data;

	public function __construct($node)
	{
		if (!isset($node))
		{
			\Analog\Analog::log("A list of nodes must be provided for each Request", \Analog\Analog::ERROR);
			throw new exceptions\BadMethodCallException("A list of nodes must be provided for each Request");
		}


		if (!is_array($node))
		{
			\Analog\Analog::log("Argument nodes must be an array.", \Analog\Analog::ERROR);
			throw new exceptions\BadMethodCallException("Argument nodes must be an array.");
		}

		$this->node = $node;
	}

	/**
	 * @return \sherlock\responses\Response
	 * @throws \sherlock\common\exceptions\RuntimeException
	 */
	public function execute()
	{
		\Analog\Analog::log("Request->execute()", \Analog\Analog::DEBUG);

		if (!isset($this->_uri))
		{
			\Analog\Analog::log("Request URI must be set.", \Analog\Analog::ERROR);
			throw new \sherlock\common\exceptions\RuntimeException("Request URI must be set.");
		}

		\Analog\Analog::log("Request->_uri: ".$this->_uri, \Analog\Analog::DEBUG);
		\Analog\Analog::log("Request->_data: ".$this->_data, \Analog\Analog::DEBUG);

		$client = new Client();
		$response = $client->post($this->_uri, null, $this->_data)->send();

		return new Response($response);
	}
}
