<?php
/**
 * User: zach
 * Date: 6/19/13
 * Time: 3:09 PM
 */

namespace Sherlock\Composers\Document;

use Sherlock\facades\Document\DocumentFacade;

/**
 * Class GetComposer
 * @package Sherlock\Composers\Document
 */
class GetComposer extends AbstractDocumentComposer
{
    /** @var DocumentComposer  */
    private $documentComposer;

    /** @var  string[] */
    private $fields;

    /** @var bool  */
    private $sourceOnly = false;

    /**
     * @param DocumentComposer $documentComposer
     *
     */
    public function __construct(DocumentComposer $documentComposer)
    {
        $this->documentComposer = $documentComposer;
    }


    /**
     * @param string $field
     *
     * @return $this
     */
    public function field($field)
    {
        $this->fields[] = $field;
        return $this;
    }


    /**
     * @param array $fields
     *
     * @return $this
     */
    public function fields($fields)
    {
        $this->fields = $fields;
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
     * @return $this
     */
    public function getSourceOnly()
    {
        $this->sourceOnly = true;
        return $this;
    }


    /**
     * @return DocumentFacade
     */
    public function enqueue()
    {
        $this->translateFieldsField();
        $this->checkSourceOnly();
        return $this->documentComposer->enqueueDelete($this->request);
    }


    /**
     * @return array
     */
    public function execute()
    {
        $this->enqueue($this->request);
        return $this->documentComposer->execute();
    }


    private function translateFieldsField()
    {
        if (count($this->fields) === 0) {
            return;
        }

        $this->indices = implode(",", $this->fields);
        $this->request['fields'] = $this->fields;
        unset($this->indices);
    }

    private function checkSourceOnly()
    {
        if ($this->sourceOnly === true) {
            $this->request['id'] .= "/_source";
        }
    }



}