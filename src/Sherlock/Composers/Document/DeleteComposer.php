<?php
/**
 * User: zach
 * Date: 6/19/13
 * Time: 12:23 PM
 */

namespace Sherlock\Composers\Document;

use Sherlock\facades\Document\DocumentFacade;


class DeleteComposer extends AbstractDocumentComposer
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
     * @return $this
     */
    public function refreshAfterDelete()
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
     * @param string $value
     * @return $this
     */
    public function timeout($value)
    {
        $this->request['timeout'] = $value;
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