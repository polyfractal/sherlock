<?php
/**
 * User: zach
 * Date: 6/18/13
 * Time: 4:58 PM
 */

namespace Sherlock\facades\Document;


use Elasticsearch\Client;
use Sherlock\Composers\Document\DeleteComposer;
use Sherlock\Composers\Document\DocumentComposer;
use Sherlock\Composers\Document\GetComposer;
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


    /**
     * @return DeleteComposer
     */
    public function delete()
    {
        return new DeleteComposer($this->documentComposer);
    }


    /**
     * @return UpdateComposer
     */
    public function update()
    {
        return new UpdateComposer($this->documentComposer);
    }


    /**
     * @return GetComposer
     */
    public function get()
    {
        return new GetComposer($this->documentComposer);
    }

    public function exists()
    {
        return new ExistsComposer($this->documentComposer);
    }


}