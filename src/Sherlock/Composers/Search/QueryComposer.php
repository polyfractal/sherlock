<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 1:40 PM
 */

namespace Sherlock\Composers\Search;


use Elasticsearch\Client;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\components\FacetInterface;
use Sherlock\components\FilterInterface;
use Sherlock\components\QueryInterface;
use Sherlock\components\SortInterface;
use Sherlock\responses\ResponseFactory;
use Sherlock\Responses\SearchResponse;

/**
 * Class QueryComposer
 * @package Sherlock\Composers\Search
 */
class QueryComposer
{
    private $requestQueue = array();
    private $currentRequest = array();

    private $transport;

    private $responseFactory;


    private $indices;
    private $types;

    /**
     * @param Client                      $transport
     * @param ResponseFactory             $responseFactory
     * @param QueryInterface|string|null  $query
     */
    public function __construct(Client $transport, ResponseFactory $responseFactory, $query = null)
    {
        $this->setQuery($query);
        $this->transport                       = $transport;
        $this->responseFactory                 = $responseFactory;

    }

    public function enqueue()
    {
        if ($this->currentRequest === array()) {
            return $this;
        }

        $this->translateIndexField();
        $this->translateTypeField();

        $this->requestQueue[] = $this->currentRequest;
        $this->currentRequest = array();

        return $this;
    }


    /**
     * @return SearchResponse[]
     */
    public function execute()
    {
        $this->enqueue();

        $responses = array();
        if (count($this->requestQueue) === 0) {
            return $responses;
        }


        foreach ($this->requestQueue as $request) {
            $responses[] = $this->executeSearch($request);
        }

        return $responses;

    }

    /**
     * @param FilterInterface $filter
     *
     * @return $this
     */
    public function filter(FilterInterface $filter)
    {
        $this->currentRequest['body']['filter'] = $filter->toArray();
        return $this;
    }


    /**
     * @param FacetInterface $facet
     *
     * @return $this
     */
    public function facet(FacetInterface $facet)
    {
        $this->currentRequest['body']['facet'] = $facet->toArray();
        return $this;
    }


    /**
     * @param SortInterface $sort
     *
     * @return $this
     */
    public function sort(SortInterface $sort)
    {
        $this->currentRequest['body']['sort'] = $sort->toArray();
        return $this;
    }

    /**
     * @param string $index
     *
     * @return $this
     */
    public function index($index)
    {
        $this->indices[] = $index;
        return $this;
    }


    /**
     * @param string[] $indices
     *
     * @return $this
     */
    public function indices($indices)
    {
        $this->indices = $indices;
        return $this;
    }


    /**
     * @param string $type
     *
     * @return $this
     */
    public function type($type)
    {
        $this->types[] = $type;
        return $this;
    }


    /**
     * @param string[] $types
     *
     * @return $this
     */
    public function types($types)
    {
        $this->types = $types;
        return $this;
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function from($value)
    {
        $this->currentRequest['body']['from'] = $value;
        return $this;
    }


    /**
     * @param int $value
     *
     * @return $this
     */
    public function size($value)
    {
        $this->currentRequest['body']['size'] = $value;
        return $this;
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function fields($fields)
    {
        $this->currentRequest['body']['fields'] = $fields;
        return $this;
    }

    /**
     * @param $parameter
     * @param $value
     *
     * @return $this
     */
    public function parameters($parameter, $value)
    {
        $this->currentRequest[$parameter] = $value;
        return $this;
    }


    /**
     * @param array $request
     *
     * @return SearchResponse
     */
    private function executeSearch($request)
    {
        $response = $this->transport->search($request);
        return $this->responseFactory->getSearchResponse($response);
    }

    private function translateIndexField()
    {
        if (count($this->indices) === 0) {
            return;
        }

        $this->indices = implode(",", $this->indices);
        $this->currentRequest['index'] = $this->indices;
        unset($this->indices);
    }

    private function translateTypeField()
    {
        if (count($this->types) === 0) {
            return;
        }

        $this->types = implode(",", $this->types);
        $this->currentRequest['type'] = $this->types;
        unset($this->types);
    }


    /**
     * @param QueryInterface|string|null $query
     */
    private function setQuery($query)
    {
        if (isset($query) === true) {
            if (is_string($query)) {
                $this->parameters('q', $query);
            } else {
                $this->currentRequest['body']['query'] = $query->toArray();
            }
        } else {
            $this->currentRequest['body']['query']['match_all'] = array();
        }
    }


}