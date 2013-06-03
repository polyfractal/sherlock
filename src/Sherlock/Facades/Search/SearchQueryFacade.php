<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 1:40 PM
 */

namespace Sherlock\Facades\Search;


use Sherlock\common\Transport;
use Sherlock\components\FacetInterface;
use Sherlock\components\FilterInterface;
use Sherlock\components\QueryInterface;
use Sherlock\components\SortInterface;
use Sherlock\Requests\SearchRequest;
use Sherlock\responses\ResponseFactory;

/**
 * Class SearchQueryFacade
 * @package Sherlock\Facades\Search
 */
class SearchQueryFacade extends SearchRequest
{
    /**
     * @param Transport       $transport
     * @param ResponseFactory $responseFactory
     * @param QueryInterface  $query
     */
    public function __construct(Transport $transport, ResponseFactory $responseFactory, QueryInterface $query)
    {
        $this->query = $query->toArray();

        parent::__construct($transport, $responseFactory);

    }


    /**
     * @param FilterInterface $filter
     *
     * @return $this
     */
    public function filter(FilterInterface $filter)
    {
        $this->filter = $filter->toArray();
        return $this;
    }


    /**
     * @param FacetInterface $facet
     *
     * @return $this
     */
    public function facet(FacetInterface $facet)
    {
        $this->facet = $facet->toArray();
        return $this;
    }


    /**
     * @param SortInterface $sort
     *
     * @return $this
     */
    public function sort(SortInterface $sort)
    {
        $this->sort = $sort->toArray();
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
        $params = array('size', 'from');
        $activeParams = array();

        foreach ($params as $param)
        {
            if (isset($this->$param) === true)
            {
                $activeParams[$param] = $this->$param;
            }
        }

        return $activeParams;
    }


}