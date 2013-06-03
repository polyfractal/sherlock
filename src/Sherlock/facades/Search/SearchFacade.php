<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 12:50 PM
 */

namespace Sherlock\search\facades;

use Sherlock\common\Transport;
use Sherlock\components\QueryInterface;
use Sherlock\Facades\Search\SearchQueryFacade;
use Sherlock\Facades\Search\SearchWhereFacade;
use Sherlock\responses\ResponseFactory;


/**
 * Class SearchFacade
 * @package Sherlock\Facades
 */
class SearchFacade
{
    private $transport;

    /** @var ResponseFactory  */
    private $responseFactory;


    /**
     * @param Transport       $transport
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Transport $transport, ResponseFactory $responseFactory)
    {
        $this->transport = $transport;
        $this->responseFactory = $responseFactory;
    }


    /**
     * @param QueryInterface $query
     *
     * @return SearchWhereRequest
     */
    public function where(QueryInterface $query)
    {
        return new SearchWhereFacade($this->transport, $this->responseFactory, $query);
    }

    public function query(QueryInterface $query)
    {
        return new SearchQueryFacade($this->transport, $this->responseFactory, $query);
    }


    /**
     * @param array $query
     *
     * @return SearchRawFacade
     */
    public function raw($query)
    {
        return new SearchRawFacade($this->transport, $this->responseFactory, $query);
    }
}