<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 12:50 PM
 */

namespace Sherlock\Facades\Search;

use Elasticsearch\Client;
use Sherlock\components\QueryInterface;
use Sherlock\Composers\Search\QueryComposer;
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
     * @param \Elasticsearch\Client $transport
     * @param ResponseFactory       $responseFactory
     */
    public function __construct(Client $transport, ResponseFactory $responseFactory)
    {
        $this->transport = $transport;
        $this->responseFactory = $responseFactory;
    }


    /**
     * @param QueryInterface $query
     *
     * @return QueryComposer
     */
    public function query(QueryInterface $query)
    {
        return new QueryComposer($this->transport, $this->responseFactory, $query);
    }

}