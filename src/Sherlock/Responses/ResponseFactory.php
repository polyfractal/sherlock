<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 5:07 PM
 */

namespace Sherlock\Responses;

/**
 * Class ResponseFactory
 * @package Sherlock\Responses
 */
class ResponseFactory
{
    public function __construct()
    {

    }


    /**
     * @param $response
     * @return SearchResponse
     */
    public function getSearchResponse($response)
    {
        return new SearchResponse();
    }
}