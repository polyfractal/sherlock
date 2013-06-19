<?php
/**
 * User: zach
 * Date: 6/18/13
 * Time: 5:24 PM
 */

namespace Sherlock\Composers\Document;

use Elasticsearch\Client;
use Sherlock\facades\Document\DocumentFacade;
use Sherlock\Responses\ResponseFactory;

/**
 * Class DocumentComposer
 * @package Sherlock\Composers\Document
 */
class DocumentComposer
{
    private $transport;

    private $responseFactory;

    /** @var  DocumentFacade */
    private $facade;

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
     * @param $request
     *
     * @return DocumentFacade
     */
    public function enqueue($request)
    {
        return $this->facade;
    }

    public function execute()
    {

    }
}