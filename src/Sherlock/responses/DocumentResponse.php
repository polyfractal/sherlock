<?php
/**
 * User: Jim Heys
 * Date: 6/18/13
 * Time: 7:56 AM
 * @package Sherlock\requests
 */
namespace Sherlock\responses;

class DocumentResponse extends Response
{

    /**
     * @param  \Sherlock\common\tmp\RollingCurl\Request           $response
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     */
    public function __construct($response)
    {
        parent::__construct($response);

        foreach ($this->responseData as $key => $value) {
            if (substr($key, 0, 1) == '_') {
                $this->responseData[ltrim($key, '_')] = $value;
                unset($this->responseData[$key]);
            }
        }
    }
}
