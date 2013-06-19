<?php
/**
 * User: zach
 * Date: 6/18/13
 * Time: 5:24 PM
 */

namespace Sherlock\Composers\Document;

use Elasticsearch\Client;
use Sherlock\common\exceptions\InvalidArgumentException;
use Sherlock\facades\Document\DocumentFacade;
use Sherlock\Responses\ResponseFactory;

/**
 * Class DocumentComposer
 * @package Sherlock\Composers\Document
 */
class DocumentComposer
{
    /** @var \Elasticsearch\Client  */
    private $transport;

    /** @var \Sherlock\Responses\ResponseFactory  */
    private $responseFactory;

    /** @var  DocumentFacade */
    private $facade;

    /** @var array  */
    private $requestQueue = array();


    /**
     * @param Client          $transport
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Client $transport, ResponseFactory $responseFactory)
    {
        $this->transport       = $transport;
        $this->responseFactory = $responseFactory;

    }


    /**
     * @param DocumentFacade $facade
     */
    public function setFacade(DocumentFacade $facade)
    {
        $this->facade = $facade;
    }


    /**
     * @param array $request
     *
     * @return DocumentFacade
     */
    public function enqueueIndex($request)
    {
        $this->checkEnqueuedRequest($request);
        $request = array('index' => $request);
        return $this->enqueue($request);
    }


    /**
     * @param array $request
     *
     * @return DocumentFacade
     */
    public function enqueueDelete($request)
    {
        $this->checkEnqueuedRequest($request);
        $request = array('delete' => $request);
        return $this->enqueue($request);
    }


    /**
     * @return array
     */
    public function execute()
    {
        $responses = array();
        if (count($this->requestQueue) === 0) {
            return $responses;
        }

        foreach ($this->requestQueue as $request) {
            $responses[] = $this->executeDocumentMethod($request);
        }

        return $responses;
    }


    /**
     * @param array $request
     *
     * @return array
     */
    private function executeDocumentMethod($request)
    {
        reset($request);
        $key   = key($request);

        switch ($key) {
            case 'index':
                return $this->transport->index($request);

            case 'delete':
                return $this->transport->delete($request);

            default:
                return array();
        }
    }


    /**
     * @param $request
     *
     * @return DocumentFacade
     */
    private function enqueue($request)
    {
        $this->requestQueue[] = $request;
        return $this->facade;
    }


    /**
     * @param array $request
     *
     * @throws \Sherlock\common\exceptions\InvalidArgumentException
     */
    private function checkEnqueuedRequest($request)
    {
        if (is_array($request) !== true || count($request) === 0) {
            throw new InvalidArgumentException('Cannot enqueue an empty request.');
        }
    }
}