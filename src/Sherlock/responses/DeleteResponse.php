<?php

namespace Sherlock\responses;

/**
 * Class DeleteResponse
 * @package Sherlock\responses
 */
class DeleteResponse extends Response
{
    /**
     * @var bool
     */
    public $found;

    /**
     * @param \Sherlock\common\tmp\RollingCurl\Request $response
     */
    public function __construct($response)
    {
        parent::__construct($response);

        if (isset($this->responseData['found']))
            $this->ok = $this->responseData['found'];

    }

}
