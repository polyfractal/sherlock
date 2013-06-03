<?php
/**
 * User: Zachary Tong
 * Date: 2/10/13
 * Time: 12:10 PM
 * @package Sherlock\Requests
 */
namespace Sherlock\Requests;




use Sherlock\common\exceptions\InvalidArgumentException;
use Sherlock\components\FacetInterface;
use Sherlock\components\FilterInterface;
use Sherlock\components\HighlightInterface;
use Sherlock\components\QueryInterface;
use Sherlock\components\SortInterface;

abstract class SearchRequest extends Request
{

    /** @var  array */
    protected $index;

    /** @var  array */
    protected $type;

    /** @var  QueryInterface */
    protected $query;

    /** @var  FilterInterface */
    protected $filter;

    /** @var  FacetInterface */
    protected $facet;

    /** @var  SortInterface */
    protected $sort;

    /** @var  HighlightInterface */
    protected $highlighter;

    /** @var  array */
    protected $fields;

    /** @var  int */
    protected $timeout;

    /** @var  int */
    protected $from;

    /** @var  int */
    protected $size;

    /** @var  string */
    protected $searchType;

    /** @var  string */
    protected $routing;


    /**
     * @return array
     */
    abstract protected function getQueryArray();


    /**
     * @return array
     */
    abstract protected function getParamArray();


    /**
     * @return array
     */
    public function get()
    {
        $query  = $this->getQueryArray();
        $params = $this->getParamArray();
        $index  = $this->getIndex();
        $type   = $this->getType();

        $response = $this->transport->search(
            $query,
            $index,
            $type,
            $params
        );

        return $this->responseFactory->getSearchResponse($response);
    }


    /**
     * @return string
     */
    private function getIndex()
    {
        if (isset($this->index) === true && $this->index !== '') {
            return implode(",", $this->index);
        } else {
            return '';
        }

    }


    /**
     * @return string
     */
    private function getType()
    {
        if (isset($this->type) === true) {
            return implode(",", $this->type);
        } else {
            return '';
        }

    }




}
