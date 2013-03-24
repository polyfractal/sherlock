<?php
/**
 * User: Zachary Tong
 * Date: 2/12/13
 * Time: 9:18 PM
 */

namespace Sherlock\responses;

use Analog\Analog;
use Sherlock\common\exceptions;

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
     * @var array
     */
    public $responseInfo;

    /**
     * @var array
     */
    public $responseError;


    /**
     * @param  \Sherlock\common\tmp\RollingCurl\Request $response
     * @throws \Sherlock\common\exceptions\ServerErrorResponseException
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     */
    public function __construct($response)
    {
        if (!isset($response)) {
            Analog::error("Response must be set in constructor.");
            throw new exceptions\BadMethodCallException("Response must be set in constructor.");
        }

        Analog::debug("Response:".print_r($response, true));

        $this->responseInfo = $response->getResponseInfo();
        $this->responseError = $response->getResponseError();

        $this->responseData = json_decode($response->getResponseText(), true);

        if ($this->responseInfo['http_code'] >= 400 && $this->responseInfo['http_code'] < 500) {
            $this->process4xx();
        } elseif ($this->responseInfo['http_code'] >= 500) {
            $this->process5xx();
        }


    }

    /**
     * @throws \Sherlock\common\exceptions\IndexAlreadyExistsException
     * @throws \Sherlock\common\exceptions\ClientErrorResponseException
     * @throws \Sherlock\common\exceptions\IndexMissingException
     */
    private function process4xx()
    {
        $error = $this->responseData['error'];
        Analog::error($error);

        if (strpos($error, "IndexMissingException") !== false) {
            throw new exceptions\IndexMissingException($error);
        } elseif (strpos($error, "IndexAlreadyExistsException") !== false) {
            throw new exceptions\IndexAlreadyExistsException($error);
        } else {
            throw new exceptions\ClientErrorResponseException($error);
        }

    }

    /**
     * @throws \Sherlock\common\exceptions\ServerErrorResponseException
     * @throws \Sherlock\common\exceptions\SearchPhaseExecutionException
     */
    private function process5xx()
    {
        $error = $this->responseData['error'];
        Analog::error($error);

        if (strpos($error, "SearchPhaseExecutionException") !== false) {
            throw new exceptions\SearchPhaseExecutionException($error);
        } else {
            throw new exceptions\ServerErrorResponseException($error);
        }

    }
}
