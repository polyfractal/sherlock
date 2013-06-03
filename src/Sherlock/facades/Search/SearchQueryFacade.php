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

    public function __construct(Transport $transport, ResponseFactory $responseFactory, QueryInterface $query)
    {
        $this->query = $query;

        parent::__construct($transport, $responseFactory);

    }

    public function filter(FilterInterface $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    public function facet(FacetInterface $facet)
    {
        $this->facet = $facet;
        return $this;
    }

    public function sort(SortInterface $sort)
    {
        $this->sort = $sort;
        return $this;
    }

    public function from($value)
    {
        $this->from = $value;
        return $this;
    }

    public function size($value)
    {
        $this->size = $value;
        return $this;
    }

    public function timeout($value)
    {
        $this->timeout = $value;
        return $this;
    }

    public function index($index)
    {
        $this->index[] = $index;
        return $this;
    }

    public function indices($indices)
    {
        $this->index = $indices;
        return $this;
    }

    public function type($type)
    {
        $this->type[] = $type;
        return $this;
    }

    public function types($types)
    {
        $this->type = $types;
        return $this;
    }



    protected function getQueryArray()
    {
        return $this->query->toArray();
    }

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