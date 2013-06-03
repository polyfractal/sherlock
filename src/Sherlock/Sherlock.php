<?php
/**
 * User: Zachary Tong
 * Date: 2/4/13
 * Time: 10:28 AM
 * @package Sherlock
 * @author  Zachary Tong
 * @version 0.1.2
 */

namespace Sherlock;

use Sherlock\common\Cluster;
use Sherlock\common\events\Events;
use Sherlock\requests;
use Sherlock\common\exceptions;
use Sherlock\facades;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Sherlock
 * @package Sherlock
 */
class Sherlock
{
    /**
     * @var array Sherlock settings, can be replaced with user-settings through constructor
     */
    protected $settings;
    /**
     * @var array Templates - not used at the moment
     */
    protected $templates = array();


    /**
     * Sherlock constructor, accepts optional user settings
     *
     * @param array $userSettings Optional user settings to over-ride the default
     */
    public function __construct($userSettings = array())
    {
        $this->initializeSherlock($userSettings);
        $this->autodetectClusterState();
    }


    /********************************************************************************
     * PSR-0 Autoloader
     *
     * Do not use if you are using Composer to autoload dependencies.
     *******************************************************************************/

    /**
     * Sherlock PSR-0 autoloader
     */
    public static function autoload($className)
    {
        $thisClass = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);

        $baseDir = __DIR__;

        if (substr($baseDir, -strlen($thisClass)) === $thisClass) {
            $baseDir = substr($baseDir, 0, -strlen($thisClass));
        }

        $className = ltrim($className, '\\');
        $fileName  = $baseDir;
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if (file_exists($fileName)) {
            require $fileName;
        }
    }


    /**
     * Register Sherlock's PSR-0 autoloader
     */
    public static function registerAutoloader()
    {
        spl_autoload_register(__NAMESPACE__ . "\\Sherlock::autoload");
    }


    /**
     * Filter builder, used to return a FilterFacade through which a Filter component can be selected
     * @return facades\FilterFacade
     */
    public static function filterBuilder()
    {
        return new facades\FilterFacade();
    }


    /**
     * Facet builder, used to return a FilterFacade through which a Filter component can be selected
     * @return facades\FacetFacade
     */
    public static function facetBuilder()
    {
        return new facades\FacetFacade();
    }


    /**
     * Highlight builder, used to return a HighlightFacade through which a Highlight component can be selected
     * @return facades\HighlightFacade
     */
    public static function highlightBuilder()
    {
        return new facades\HighlightFacade();
    }


    /**
     * Index builder, used to return a IndexFacade through which an Index component can be selected
     * @return facades\IndexSettingsFacade
     */
    public static function indexSettingsBuilder()
    {
        return new facades\IndexSettingsFacade();
    }


    /**
     * Mapping builder, used to return a MappingFacade through which a Mapping component can be selected
     *
     * @param  null|string                     $type
     *
     * @return facades\MappingPropertyFacade
     */
    public static function mappingBuilder($type = null)
    {
        return new facades\MappingPropertyFacade($type);
    }


    /**
     * @return facades\SortFacade
     */
    public static function sortBuilder()
    {
        return new facades\SortFacade();
    }


    /**
     * Used to obtain a SearchRequest object, allows querying the cluster with searches
     * @return requests\SearchRequest
     */
    public function search()
    {
        return new requests\SearchRequest($this->settings['event.dispatcher']);
    }


    /**
     * RawRequests allow the user to issue arbitrary commands to the ES cluster
     * Effectively one step above a raw CURL command
     *
     * @return requests\RawRequest
     */
    public function raw()
    {
        return new requests\RawRequest($this->settings['event.dispatcher']);
    }


    /**
     * Used to return an IndexDocumentRequest object, allows adding a doc to the index
     * @return requests\IndexDocumentRequest
     */
    public function document()
    {
        return new requests\IndexDocumentRequest($this->settings['event.dispatcher']);
    }


    /**
     * Used to return a DeleteDocumentRequest object, allows deleting a doc from the index
     * @return requests\DeleteDocumentRequest
     */
    public function deleteDocument()
    {
        return new requests\DeleteDocumentRequest($this->settings['event.dispatcher']);
    }


    /**
     * @param  string                $index     Index to operate on
     * @param  string                $index,... Index to operate on
     *
     * @return requests\IndexRequest
     */
    public function index($index = null)
    {
        $args  = func_get_args();
        $index = array();
        foreach ($args as $arg) {
            $index[] = $arg;
        }

        return new requests\IndexRequest($this->settings['event.dispatcher'], $index);
    }


    /**
     * Autodetects various properties of the cluster and indices
     */
    public function autodetectClusterState()
    {
        //If we have nodes and are supposed to detect cluster settings/configuration
        if ($this->settings['cluster.autodetect'] == true) {
            $this->settings['cluster']->autodetect();
        }
    }


    /**
     * Add a new node to the ES cluster
     *
     * @param  string                                     $host server host address (either IP or domain)
     * @param  int                                        $port ElasticSearch port (defaults to 9200)
     *
     * @return \Sherlock\Sherlock
     * @throws common\exceptions\BadMethodCallException
     * @throws common\exceptions\InvalidArgumentException
     */
    public function addNode($host, $port = 9200)
    {
        $this->settings['cluster']->addNode($host, $port, $this->settings['cluster.autodetect']);

        return $this;
    }


    /**
     * @return array
     */
    public function getSherlockSettings()
    {
        return $this->settings;
    }


    /**
     * @param $userSettings
     */
    private function initializeSherlock($userSettings)
    {
        $this->mergeUserSettingsWithDefault($userSettings);
        $this->initializeCluster();
        $this->initializeEventDispatcher();
    }

    /**
     * @param array $userSettings
     */
    private function mergeUserSettingsWithDefault($userSettings)
    {
        $this->settings = array_merge($this->getDefaultSettings(), $userSettings);
    }

    private function initializeCluster()
    {
        $this->settings['cluster'] = new Cluster($this->settings['event.dispatcher']);
    }

    private function initializeEventDispatcher()
    {
        $eventCallback = array($this->settings['cluster'], 'onRequestExecute');
        $this->settings['event.dispatcher']->addListener(Events::REQUEST_PREEXECUTE, $eventCallback);
    }

    /**
     * @return array Default settings
     */
    private function getDefaultSettings()
    {
        return array(
            // Application
            'base'               => __DIR__ . '/',
            'mode'               => 'development',
            'event.dispatcher'   => new EventDispatcher(),
            'cluster'            => null,
            'cluster.autodetect' => false,
        );
    }

}
