<?php
/**
 * User: zach
 * Date: 6/18/13
 * Time: 5:13 PM
 */

namespace Sherlock\Composers\Document;

use Elasticsearch\Common\Exceptions\UnexpectedValueException;

/**
 * Class AbstractDocumentComposer
 * @package Sherlock\Composers\Document
 */
abstract class AbstractDocumentComposer
{

    protected $request;

    abstract public function enqueue();
    abstract public function execute();

    /**
     * @param string $index
     *
     * @return $this
     */
    public function index($index)
    {
        $this->request['index'] = $index;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function type($type)
    {
        $this->request['type'] = $type;
        return $this;
    }

    /**
     * @param string $docID
     *
     * @return $this
     */
    public function docID($docID)
    {
        $this->request['id'] = $docID;
        return $this;
    }


    /**
     * @param string $param
     * @param string[] $whitelist
     *
     * @throws UnexpectedValueException
     */
    protected function validateParams($param, $whitelist)
    {
        if (array_search($param, $whitelist) === false) {
            throw new UnexpectedValueException($param . ' is not a valid parameter');
        }

    }


}