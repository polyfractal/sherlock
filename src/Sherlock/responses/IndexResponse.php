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

		if (isset($this->responseData['ok']))
			$this->ok = $this->responseData['ok'];

		if (isset($this->responseData['acknowledged']))
			$this->acknowledged = $this->responseData['acknowledged'];
	}

}