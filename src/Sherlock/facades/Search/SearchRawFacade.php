<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 5:24 PM
 */

namespace Sherlock\search\facades;


use Sherlock\common\Transport;
use Sherlock\Requests\SearchRequest;
use Sherlock\responses\ResponseFactory;

/**
 * Class SearchRawFacade
 * @package Sherlock\search\facades
 */
class SearchRawFacade extends SearchRequest
{
    /** @var  array */
    private $params;


    /**
     * @param Transport       $transport
     * @param ResponseFactory $responseFactory
     * @param array           $body
     */
    public function __construct(Transport $transport, ResponseFactory $responseFactory, $body)
    {
        $this->query = $body;

        parent::__construct($transport, $responseFactory);

    }


    /**
     * @param array $params
     * @return $this
     */
    public function params($params)
    {
        $this->params = $params;
        return $this;
    }


    /**
     * @return array
     */
    protected function getQueryArray()
    {
        return $this->query;
    }


    /**
     * @return array
     */
    protected function getParamArray()
    {
        return $this->params;
    }
}