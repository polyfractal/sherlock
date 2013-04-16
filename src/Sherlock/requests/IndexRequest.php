<?php
/**
 * User: Zachary Tong
 * Date: 2/12/13
 * Time: 7:37 PM
 * @package Sherlock\requests
 */

namespace Sherlock\requests;

use Analog\Analog;
use Sherlock\common\exceptions;
use Sherlock\wrappers;

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

    protected $dispatcher;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     * @param $index
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @internal param $node
     */
    public function __construct($dispatcher, $index)
    {
        if (!isset($dispatcher))
            throw new \Sherlock\common\exceptions\BadMethodCallException("Dispatcher argument required for IndexRequest");
        if (!isset($index))
            throw new \Sherlock\common\exceptions\BadMethodCallException("Index argument required for IndexRequest");

        $this->dispatcher = $dispatcher;

        if(!is_array($index))
            $this->params['index'][] = $index;
        else
            $this->params['index'] = $index;

        $this->params['indexSettings'] = array();
        $this->params['indexMappings'] = array();

        parent::__construct($dispatcher);
    }

    /**
     * @param $name
     * @param $args
     * @return IndexRequest
     */
    public function __call($name, $args)
    {
        $this->params[$name] = $args[0];

        return $this;
    }

    /**
     * ---- Settings / Parameters ----
     * Various settings and parameters to be set before invoking an action
     * Returns $this
     *
     */

    /**
     * Set the index to operate on
     *
     * @param  string       $index     indices to operate on
     * @param  string       $index,... indices to operate on
     * @return IndexRequest
     */
    public function index($index)
    {
        $this->params['index'] = array();
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->params['index'][] = $arg;
        }

        return $this;
    }

    /**
     * Set the type to operate on
     *
     * @param  string       $type     indices to operate on
     * @param  string       $type,... indices to operate on
     * @return IndexRequest
     */
    public function type($type)
    {
        $this->params['type'] = array();
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->params['type'][] = $arg;
        }

        return $this;
    }

    /**
     * Set the mappings that are used for various operations (set mappings, index creation, etc)
     *
     * @todo fix array-only input
     * @todo add json input
     *
     * @param  array|\sherlock\components\MappingInterface|bool   $mapping,...
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     * @return \Sherlock\requests\IndexRequest
     */
    public function mappings($mapping)
    {
        $append = false;
        $args = func_get_args();
        foreach ($args as $arg) {
            //if there is a "true" bool in the arg list, append instead of overwrite
            //Defaults to overwrite
            if (is_bool($arg) && $arg == true) {
                $append = true;
                break;
            }
        }
        if ($append == false)
            $this->params['indexMappings'] = array();

        foreach ($args as $arg) {

            if ($arg instanceof \Sherlock\components\MappingInterface) {
                if (isset($this->params['indexMappings'][$arg->getType()])) {
                    $this->params['indexMappings'][$arg->getType()] = array_merge($this->params['indexMappings'][$arg->getType()], $arg->toArray());
                } else {
                    $this->params['indexMappings'][$arg->getType()] = $arg->toArray();
                }
            } elseif (is_array($arg)) {
                foreach($arg as $argMapping) {
                    if (isset($this->params['indexMappings'][$argMapping->getType()])) {
                        $this->params['indexMappings'][$argMapping->getType()] = array_merge($this->params['indexMappings'][$argMapping->getType()], $argMapping->toArray());
                    } else {
                        $this->params['indexMappings'][$argMapping->getType()] = $argMapping->toArray();
                    }
                }
            } elseif (is_bool($arg)) {
                continue;
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
        Analog::debug("IndexRequest->execute() - ".print_r($this->params, true));

        if (!isset($this->params['index']))
            throw new exceptions\RuntimeException("Index cannot be empty.");

        $index = implode(',', $this->params['index']);

        $command = new Command();
        $command->index($index)
                ->action('delete');

        $this->batch->clearCommands();
        $this->batch->addCommand($command);

        $ret =  parent::execute();

        return $ret[0];
    }
    /**
     * Create an index
     *
     * @return \Sherlock\responses\IndexResponse
     * @throws exceptions\RuntimeException
     */
    public function create()
    {
        Analog::log("IndexRequest->create() - ".print_r($this->params, true), Analog::DEBUG);

        if (!isset($this->params['index']))
            throw new exceptions\RuntimeException("Index cannot be empty.");

        $index = implode(',', $this->params['index']);

        //Final JSON should be object properties, not an array.  So we need to iterate
        //through the array members and merge into an associative array.
        $mappings = array();
        foreach ($this->params['indexMappings'] as $type => $mapping) {
            $mappings = array_merge($mappings, array($type => array("properties" => $mapping)));
        }
        $body = array("settings" => $this->params['indexSettings'],
                        "mappings" => $mappings);

        $command = new Command();
        $command->index($index)
                ->action('put')
                ->data(json_encode($body, JSON_FORCE_OBJECT));

        $this->batch->clearCommands();
        $this->batch->addCommand($command);

        /**
         * @var \Sherlock\responses\IndexResponse
         */
        $ret =  parent::execute();

        return $ret[0];
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
        Analog::log("IndexRequest->updateSettings() - ".print_r($this->params, true), Analog::DEBUG);

        if (!isset($this->params['index'])) {
            Analog::log("Index cannot be empty.", Analog::ERROR);
            throw new exceptions\RuntimeException("Index cannot be empty.");
        }

        $index = implode(',', $this->params['index']);

        $body = array("index" => $this->params['indexSettings']);

        $command = new Command();
        $command->index($index)
                ->id('_settings')
                ->action('put')
                ->data(json_encode($body, JSON_FORCE_OBJECT));

        $this->batch->clearCommands();
        $this->batch->addCommand($command);

        $ret =  parent::execute();

        return $ret[0];

    }

    /**
     * Update/add the Mapping of an index
     *
     * @return \Sherlock\responses\IndexResponse
     * @throws exceptions\RuntimeException
     */
    public function updateMapping()
    {
        Analog::log("IndexRequest->updateMapping() - ".print_r($this->params, true), Analog::DEBUG);

        if (!isset($this->params['index'])) {
            Analog::log("Index cannot be empty.", Analog::ERROR);
            throw new exceptions\RuntimeException("Index cannot be empty.");
        }

        if (count($this->params['indexMappings']) > 1) {
            Analog::log("May only update one mapping at a time.", Analog::ERROR);
            throw new exceptions\RuntimeException("May only update one mapping at a time.");
        }

        if (!isset($this->params['type'])) {
            Analog::log("Type must be specified.", Analog::ERROR);
            throw new exceptions\RuntimeException("Type must be specified.");
        }

        if (count($this->params['type']) > 1) {
            Analog::log("Only one type may be updated at a time.", Analog::ERROR);
            throw new exceptions\RuntimeException("Only one type may be updated at a time.");
        }

        $index = implode(',', $this->params['index']);
        $body = $this->params['indexMappings'];

        $command = new Command();
        $command->index($index)
                ->type($this->params['type'][0])
                ->id('_mapping')
                ->action('put')
                ->data(json_encode($body, JSON_FORCE_OBJECT));

        $this->batch->clearCommands();
        $this->batch->addCommand($command);

        $ret =  parent::execute();

        return $ret[0];
    }
}
