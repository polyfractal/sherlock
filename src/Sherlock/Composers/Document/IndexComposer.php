<?php

namespace Sherlock\Composers\Document;

use Sherlock\facades\Document\DocumentFacade;

/**
 * User: zach
 * Date: 6/18/13
 * Time: 5:10 PM
 */

class IndexComposer extends AbstractDocumentComposer
{
    /** @var DocumentComposer  */
    private $documentComposer;


    /**
     * @param DocumentComposer $documentComposer
     *
     */
    public function __construct(DocumentComposer $documentComposer)
    {
        $this->documentComposer = $documentComposer;
    }


    /**
     * @param string|array $document
     *
     * @return $this
     */
    public function document($document)
    {
        $this->request['body'] = $document;
        return $this;
    }


    /**
     * @return $this
     */
    public function createOnlyIfAbsent()
    {
        $this->request['op_type'] = 'create';
        return $this;
    }


    /**
     * @param string $value
     * @return $this
     */
    public function consistency($value)
    {
        $whitelist = array(
            'one',
            'quorum',
            'all'
        );

        $this->validateParams($value, $whitelist);
        $this->request['consistency'] = $value;
        return $this;
    }


    /**
     * @param string $value
     * @return $this
     */
    public function parent($value)
    {
        $this->request['parent'] = $value;
        return $this;
    }


    /**
     * @param string $value
     * @return $this
     */
    public function percolate($value)
    {
        $this->request['percolate'] = $value;
        return $this;
    }


    /**
     * @return $this
     */
    public function refreshAfterIndex()
    {
        $this->request['refresh'] = 'true';
        return $this;
    }


    /**
     * @return $this
     */
    public function asyncReplication()
    {
        $this->request['replication'] = 'async';
        return $this;
    }


    /**
     * @param string $value
     * @return $this
     */
    public function routing($value)
    {
        $this->request['routing'] = $value;
        return $this;
    }


    /**
     * @param string $value
     * @return $this
     */
    public function timeout($value)
    {
        $this->request['timeout'] = $value;
        return $this;
    }


    /**
     * @param string $value
     * @return $this
     */
    public function timestamp($value)
    {
        $this->request['timestamp'] = $value;
        return $this;
    }


    /**
     * @param string $value
     * @return $this
     */
    public function ttl($value)
    {
        $this->request['ttl'] = $value;
        return $this;
    }


    /**
     * @param int $value
     * @return $this
     */
    public function version($value)
    {
        $this->request['version'] = $value;
        return $this;
    }


    /**
     * @return $this
     */
    public function versionIsExternal()
    {
        $this->request['version_type'] = 'external';
        return $this;
    }


    /**
     * @return DocumentFacade
     */
    public function enqueue()
    {
        $documentComposer = $this->documentComposer->enqueue($this->request);
        return $documentComposer;
    }

    public function execute()
    {
        return $this->documentComposer->execute();
    }


}