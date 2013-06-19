<?php
/**
 * User: zach
 * Date: 6/19/13
 * Time: 3:49 PM
 */

namespace Sherlock\Composers\Document;

use Sherlock\Facades\Document\DocumentFacade;

/**
 * Class ExistsComposer
 * @package Sherlock\Composers\Document
 */
class ExistsComposer extends AbstractDocumentComposer
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
    public function preference($value)
    {
        $this->request['preference'] = $value;
        return $this;
    }

    /**
     * @return $this
     */
    public function disableRealtimeGet()
    {
        $this->request['realtime'] = 'false';
        return $this;
    }


    /**
     * @return $this
     */
    public function refreshBeforeGet()
    {
        $this->request['refresh'] = 'true';
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
     * @return DocumentFacade
     */
    public function enqueue()
    {
        return $this->documentComposer->enqueueExists($this->request);
    }


    /**
     * @return array
     */
    public function execute()
    {
        $this->enqueue($this->request);
        return $this->documentComposer->execute();
    }



}