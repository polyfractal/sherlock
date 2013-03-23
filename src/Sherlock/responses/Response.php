<?php
/**
 * User: Zachary Tong
 * Date: 2/12/13
 * Time: 9:18 PM
 */

namespace Sherlock\responses;

use Analog\Analog;
use Guzzle\Http\Message;
use Sherlock\common\exceptions\BadMethodCallException;

/**
 * Class Response
 * @package Sherlock\responses
 */
class Response
{
    /**
     * @var array
     */
    public $responseData;

    /**
     * @param  \Sherlock\common\tmp\RollingCurl\Request                      $response
     * @throws BadMethodCallException
     */
    public function __construct($response)
    {
        if (!isset($response)) {
            Analog::error("Response must be set in constructor.");
            throw new BadMethodCallException("Response must be set in constructor.");
        }

        $this->responseData = json_decode($response->getResponseText(), true);

        Analog::debug("Response:".print_r($response, true));
    }
}
