<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 8:54 AM
 */

namespace sherlock\requests;
use sherlock\common\exceptions;
use sherlock\responses\IndexResponse;
use Analog\Analog;
use Guzzle\Http\Client;

class Request
{
	protected $node;

	//required since PHP doesn't allow argument differences between
	//parent and children under Strict
	protected $_uri;
	protected $_data;
	protected $_action;

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
	 * @throws \sherlock\common\exceptions\RuntimeException
	 * @throws \Guzzle\Http\Exception\BadResponseException
	 * @return \sherlock\responses\Response
	 */
	public function execute()
	{
		$reflector = new \ReflectionClass(get_class($this));
		$class = $reflector->getShortName();

		\Analog\Analog::log("Request->execute()", \Analog\Analog::DEBUG);

		if (!isset($this->_uri))
		{
			\Analog\Analog::log("Request URI must be set.", \Analog\Analog::ERROR);
			throw new \sherlock\common\exceptions\RuntimeException("Request URI must be set.");
		}

		\Analog\Analog::log("Request->_uri: ".$this->_uri, \Analog\Analog::DEBUG);
		\Analog\Analog::log("Request->_data: ".$this->_data, \Analog\Analog::DEBUG);

		$client = new Client();
		$action = $this->_action;
		try {
			$response = $client->$action($this->_uri, null, $this->_data)->send();

		}
		/*catch (\Guzzle\Http\Exception\ClientErrorResponseException $e){
			echo "Message----------------------------\r\n";
			print_r($e->getMessage());

			echo "Response Body----------------------------\r\n";


			print_r($e->getResponse()->getBody(true));
			throw $e;
		}       */
		catch (\Guzzle\Http\Exception\BadResponseException $e) {
			//error!
			Analog::log("Request->execute() - Request failed from ".$class.' '.print_r($e->getMessage(), true), Analog::ERROR);
			throw $e;
		}

		//This is kinda gross...
		if ($class == 'SearchRequest')
			$ret =  new \sherlock\responses\QueryResponse($response);
		elseif ($class == 'IndexRequest')
			$ret =  new \sherlock\responses\IndexResponse($response);
		elseif ($class == 'IndexDocumentRequest')
			$ret =  new \sherlock\responses\IndexResponse($response);

		return $ret;
	}
}
