<?php
/**
 * User: zach
 * Date: 6/18/13
 * Time: 4:58 PM
 */

namespace Sherlock\facades\Document;


use Elasticsearch\Client;
use Sherlock\Composers\Document\DocumentComposer;
use Sherlock\Composers\Document\IndexComposer;
use Sherlock\Responses\ResponseFactory;

/**
 * Class DocumentFacade
 * @package Sherlock\facades\Document
 */
class DocumentFacade
{
    private $documentComposer;


    /**
     * @param \Sherlock\Composers\Document\DocumentComposer $documentComposer
     */
    public function __construct(DocumentComposer $documentComposer)
    {
        $this->documentComposer = $documentComposer;
        $this->documentComposer->setFacade($this);
    }


    /**
     * @return IndexComposer
     */
    public function index()
    {
        return new IndexComposer($this->documentComposer);
    }

    public function delete()
    {

    }

    public function update()
    {

    }
}