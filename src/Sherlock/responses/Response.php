<?php
/**
 * User: Zachary Tong
 * Date: 2/11/13
 * Time: 7:27 PM
 */

namespace sherlock\responses;
use Guzzle\Http\Message;

class Response
{

	protected $responseData;

	/**
	 * @param \Guzzle\Http\Message\Response $response
	 */
	public function __construct($response)
	{
		$this->responseData = $response->json();
	}


}
