<?php
/**
 * User: Zachary Tong
 * Date: 2/12/13
 * Time: 9:18 PM
 */

namespace sherlock\responses;
use Guzzle\Http\Message;

class Response
{
	/**
	 * @var array
	 */
	public $responseData;

	/**
	 * @param \Guzzle\Http\Message\Response $response
	 * @throws \sherlock\common\exceptions\BadMethodCallException
	 */
	public function __construct($response)
	{
		if (!isset($response))
		{
			\Analog\Analog::log("Response must be set in constructor.",\Analog\Analog::ERROR);
			throw new \sherlock\common\exceptions\BadMethodCallException("Response must be set in constructor.");
		}


		//\Analog\Analog::log("Response->__construct() : ".print_r($this->responseData,true),\Analog\Analog::DEBUG);
		print_r($response->getBody(true));

		//$this->responseData = $response->json();
	}
}
