<?php
/**
 * User: Zachary Tong
 * Date: 2/12/13
 * Time: 9:18 PM
 */

namespace Sherlock\Responses;


use Sherlock\common\exceptions;

/**
 * Class Response
 * @package Sherlock\Responses
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
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     */
    public function __construct($response)
    {
        if (!isset($response)) {
            throw new exceptions\BadMethodCallException("Response must be set in constructor.");
        }

        $this->parseResponse($response);
        $this->checkForErrorsInResponse();

    }


    /**
     * @param \Sherlock\common\tmp\RollingCurl\Request $response
     */
    private function parseResponse($response)
    {
        $this->responseInfo  = $response->getResponseInfo();
        $this->responseError = $response->getResponseError();
        $this->responseData = json_decode($response->getResponseText(), true);

    }


    private function checkForErrorsInResponse()
    {
        if ($this->responseInfo['http_code'] >= 400 && $this->responseInfo['http_code'] < 500) {
            $this->process4xx();
        } elseif ($this->responseInfo['http_code'] >= 500) {
            $this->process5xx();
        }
    }

    /**
     * @throws \Exception
     */
    private function process4xx()
    {
        try {
            $this->ifDocumentMissingThrowException();
            $this->ifIndexMissingThrowException();
            $this->ifIndexExistsThrowException();
            $this->unknownClientErrorFound();
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    /**
     * @throws \Sherlock\common\exceptions\DocumentMissingException
     */
    private function ifDocumentMissingThrowException()
    {
        if (isset($this->responseData['found']) && $this->responseData['found'] === false) {
            throw new exceptions\DocumentMissingException("Document is missing from the index");
        }
    }


    /**
     * @throws \Sherlock\common\exceptions\IndexMissingException
     */
    private function ifIndexMissingThrowException()
    {
        if (strpos($this->responseData['error'], "IndexMissingException") !== false) {
            throw new exceptions\IndexMissingException($this->responseData['error']);
        }
    }


    /**
     * @throws \Sherlock\common\exceptions\IndexAlreadyExistsException
     */
    private function ifIndexExistsThrowException()
    {
        if (strpos($this->responseData['error'], "IndexAlreadyExistsException") !== false) {
            throw new exceptions\IndexAlreadyExistsException($this->responseData['error']);
        }
    }


    /**
     * @throws \Sherlock\common\exceptions\ClientErrorResponseException
     */
    private function unknownClientErrorFound()
    {
        throw new exceptions\ClientErrorResponseException($this->responseData['error']);
    }


    /**
     * @throws \Exception
     */
    private function process5xx()
    {
        try {
            $this->ifSearchPhaseErrorThrowException();
            $this->unknownServerErrorFound();
        } catch (\Exception $exception) {
            throw $exception;
        }

    }


    /**
     * @throws \Sherlock\common\exceptions\SearchPhaseExecutionException
     */
    private function ifSearchPhaseErrorThrowException()
    {
        if (strpos($this->responseData['error'], "SearchPhaseExecutionException") !== false) {
            throw new exceptions\SearchPhaseExecutionException($this->responseData['error']);
        }
    }


    /**
     * @throws \Sherlock\common\exceptions\ServerErrorResponseException
     */
    private function unknownServerErrorFound()
    {
        throw new exceptions\ServerErrorResponseException($this->responseData['error']);
    }
}
