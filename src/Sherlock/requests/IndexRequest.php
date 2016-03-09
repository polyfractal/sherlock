<?php
/**
 * User: Zachary Tong
 * Date: 2/12/13
 * Time: 7:37 PM
 * @package Sherlock\requests
 */

namespace Sherlock\requests;


use Sherlock\common\exceptions;
use Sherlock\wrappers;
use Sherlock\responses\IndexResponse;
use Elasticsearch\Client as ESClient;

/**
 * IndexRequest manages index-specific operations
 *
 * Index operations include actions like getting or updating mappings,
 * creating new indices, deleting old indices, state, stats, etc
 *
 * Note, this is distinct from the IndexDocument class, which is solely responsible
 * for indexing documents
 */
class IndexRequest extends Request
{

    /**
     * @param  \Elasticsearch\Client $esClient
     * @param                                                    $index
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @internal param $node
     */
    public function __construct($esClient, $index)
    {

        if (!is_array($index))
            $this->params['index'][] = $index;
        else
            $this->params['index'] = $index;

        $this->params['indexSettings'] = array();
        $this->params['indexMappings'] = array();

        parent::__construct($esClient);
    }


    /**
     * @param $name
     * @param $args
     *
     * @return IndexRequest
     */
    public function __call($name, $args)
    {
        $this->params[$name] = $args[0];

        return $this;
    }

    /**
     * Set the mappings that are used for various operations (set mappings, index creation, etc)
     *
     * @todo fix array-only input
     * @todo add json input
     *
     * @param  array|\sherlock\components\MappingInterface|bool   $mapping,...
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @return \Sherlock\requests\IndexRequest
     */
    public function mappings($mapping)
    {
        $args = func_get_args();


        //@todo this is all horrible, burn it and rewrite

        foreach ($args as $arg) {

            if ($arg instanceof \Sherlock\components\MappingInterface) {

                //is this a core type?  Wrap in 'properties'
                if (!($arg instanceof \Sherlock\components\mappings\Analyzer)) {
                    $mappingValue = array("properties" => $arg->toArray());
                } else {
                    $mappingValue = $arg->toArray();
                }

                if (isset($this->params['indexMappings'][$arg->getType()])) {
                    $this->params['indexMappings'][$arg->getType()] = array_merge_recursive(
                        $this->params['indexMappings'][$arg->getType()],
                        $mappingValue
                    );
                } else {
                    $this->params['indexMappings'][$arg->getType()] = $mappingValue;
                }
            } elseif (is_array($arg)) {
                foreach ($arg as $argMapping) {

                    //is this a core type?  Wrap in 'properties'
                    if (!($arg instanceof \Sherlock\components\mappings\Analyzer)) {
                        $mappingValue = array("properties" => $argMapping->toArray());
                    } else {
                        $mappingValue = $argMapping->toArray();
                    }

                    if (isset($this->params['indexMappings'][$argMapping->getType()])) {
                        $this->params['indexMappings'][$argMapping->getType()] = array_merge_recursive(
                            $this->params['indexMappings'][$argMapping->getType()],
                            $mappingValue
                        );
                    } else {
                        $this->params['indexMappings'][$argMapping->getType()] = $mappingValue;
                    }
                }
            } else {
                throw new \Sherlock\common\exceptions\BadMethodCallException("Arguments must be an array or a Mapping Property.");
            }


        }

        return $this;
    }


    /**
     * Set the index settings, used predominantly for index creation
     *
     * @param  array|\sherlock\wrappers\IndexSettingsWrapper      $settings
     * @param  bool                                               $merge
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @return IndexRequest
     */
    public function settings($settings, $merge = true)
    {
        if ($settings instanceof \Sherlock\wrappers\IndexSettingsWrapper)
            $newSettings = $settings->toArray();
        else if (is_array($settings))
            $newSettings = $settings;
        else
            throw new \Sherlock\common\exceptions\BadMethodCallException("Unknown parameter provided to settings(). Must be array of settings or IndexSettingsWrapper.");

        if ($merge)
            $this->params['indexSettings'] = array_merge($this->params['indexSettings'], $newSettings);
        else
            $this->params['indexSettings'] = $newSettings;

        return $this;
    }


    /*
     * ---- Actions -----
     * Actions are applied to the index through an HTTP request, and return a response
     *
     */

    /**
     * Delete an index
     *
     * @return \Sherlock\responses\IndexResponse
     * @throws exceptions\RuntimeException
     */
    public function delete()
    {

        if (!isset($this->params['index']))
            throw new exceptions\RuntimeException("Index cannot be empty.");

        $index = implode(',', $this->params['index']);

        $deleteParams['index'] = $index;
        $ret = $this->esClient->indices()->delete($deleteParams);

        return $ret;
    }


    /**
     * Create an index
     *
     * @return \Sherlock\responses\IndexResponse
     * @throws exceptions\RuntimeException
     */
    public function create()
    {

        if (!isset($this->params['index']))
            throw new exceptions\RuntimeException("Index cannot be empty.");

        $index = implode(',', $this->params['index']);

        //Final JSON should be object properties, not an array.  So we need to iterate
        //through the array members and merge into an associative array.
        $mappings = array();
        foreach ($this->params['indexMappings'] as $type => $mapping) {
            $mappings = array_merge($mappings, array($type => $mapping));
        }
        $settings = $this->params['indexSettings'];
        $body = array();

        // set body if arrays not empty. Empty arrays causes problems in api calls.
        if(count($mappings) > 0){
            $body["mappings"] = $mappings;
        }
        if(count($settings) > 0){
            $body["settings"] = $settings;
        }

        /**
         * @var \Sherlock\responses\IndexResponse
         */
        $indexParams = array(
            "index" => $index,
            "body" => $body
        );

        $ret =$this->esClient->indices()->create($indexParams);
        //clear out mappings, settings
        $this->resetIndex();

        return $ret;
    }


    /**
     * Update the settings of an index
     *
     * @todo allow updating settings of all indices
     *
     * @return \Sherlock\responses\IndexResponse
     * @throws exceptions\RuntimeException
     */
    public function updateSettings()
    {

        if (!isset($this->params['index'])) {
                        throw new exceptions\RuntimeException("Index cannot be empty.");
        }

        $index = implode(',', $this->params['index']);

        $body = array("index" => $this->params['indexSettings']);


        $settingsParams = array(
            "index" => $index,
            "body" => $body
        );
        $ret = $this->esClient->indices()->putSettings($settingsParams);

        //clear out mappings, settings
        //$this->resetIndex();

        return $ret;

    }


    /**
     * Update/add the Mapping of an index
     *
     * @return \Sherlock\responses\IndexResponse
     * @throws exceptions\RuntimeException
     */
    public function updateMapping()
    {

        if (!isset($this->params['index'])) {
                        throw new exceptions\RuntimeException("Index cannot be empty.");
        }

        if (count($this->params['indexMappings']) > 1) {
                        throw new exceptions\RuntimeException("May only update one mapping at a time.");
        }

        if (!isset($this->params['type'])) {
                        throw new exceptions\RuntimeException("Type must be specified.");
        }

        if (count($this->params['type']) > 1) {
                        throw new exceptions\RuntimeException("Only one type may be updated at a time.");
        }

        $index = implode(',', $this->params['index']);
        $body  = $this->params['indexMappings'];

        $mappingsParams = array(
            "type" => $this->params['type'][0],
            "index" => $index,
            "body" => $body,
        );
        $ret = $this->esClient->indices()->putMapping($mappingsParams);

        //clear out mappings, settings
        //$this->resetIndex();

        return $ret;
    }


    /**
     *
     */
    private function resetIndex()
    {
        $this->params['indexMappings'] = array();
        $this->params['indexSettings'] = array();
    }

    /**
     * @param $response
     * @return \Sherlock\responses\IndexResponse|\Sherlock\responses\Response
     */
    protected function getReturnResponse($response)
    {
        return new IndexResponse($response);
    }
}
