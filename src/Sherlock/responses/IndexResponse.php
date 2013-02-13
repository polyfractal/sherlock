<?php
/**
 * User: Zachary Tong
 * Date: 2/12/13
 * Time: 9:29 PM
 */


namespace sherlock\responses;
use Guzzle\Http\Message;

class IndexResponse extends Response
{
	/**
	 * @var int
	 */
	public $ok;

	/**
	 * @var int
	 */
	public $acknowledged;



	public function __construct($response)
	{
		parent::__construct($response);

		$this->ok = $this->responseData['ok'];
		$this->acknowledged = $this->responseData['acknowledged'];
	}

}