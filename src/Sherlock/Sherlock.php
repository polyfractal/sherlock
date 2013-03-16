<?php
/**
 * User: Zachary Tong
 * Date: 2/4/13
 * Time: 10:28 AM
 * @package Sherlock
 * @author Zachary Tong
 * @version 0.1.2
 */

namespace Sherlock;

use Sherlock\common\Cluster;
use Sherlock\common\events\Events;
use Sherlock\requests;
use Sherlock\components;
use Sherlock\common\exceptions;
use Guzzle\Http\Client;
use Analog\Analog;
use Sherlock\wrappers\SortWrapper;
use Symfony\Component\EventDispatcher\EventDispatcher;
use sherlock\components\SortComponent;

/**
 * Primary object through which the ElasticSearch cluster is accessed.
 *
 * <code>
 * require 'vendor/autoload.php';
 * $sherlock = new Sherlock();
 * </code>
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
	 * @param array $userSettings Optional user settings to over-ride the default
	 */
	public function __construct($userSettings = array())
    {

        $this->settings = array_merge(static::getDefaultSettings(), $userSettings);

		//Build a cluster and inject our dispatcher
		$this->settings['cluster'] = new Cluster($this->settings['event.dispatcher']);

		//Connect Cluster's listener to the dispatcher
		$this->settings['event.dispatcher']->addListener(Events::REQUEST_PREEXECUTE, array($this->settings['cluster'], 'onRequestExecute'));

        //setup logging
        $this->setupLogging();
        Analog::log("Settings: ".print_r($this->settings, true), Analog::DEBUG);

        $this->autodetect();
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
		$thisClass = str_replace(__NAMESPACE__.'\\', '', __CLASS__);

		$baseDir = __DIR__;

		if (substr($baseDir, -strlen($thisClass)) === $thisClass) {
			$baseDir = substr($baseDir, 0, -strlen($thisClass));
		}

		$className = ltrim($className, '\\');
		$fileName  = $baseDir;
		$namespace = '';
		if ($lastNsPos = strripos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
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
	 * @return array Default settings
	 */
	private static function getDefaultSettings()
    {
        return array(
            // Application
            'base' => __DIR__.'/',
            'mode' => 'development',
			'log.enabled' => false,
            'log.level' => 'error',
			'log.handler' => null,
            'log.file' => '../sherlock.log',
			'event.dispatcher' => new EventDispatcher(),
			'cluster' => null,
			'cluster.autodetect' => false,
            );
    }

    /**
	 * Query builder, used to return a QueryWrapper through which a Query component can be selected
     * @return wrappers\QueryWrapper
     */
    public static function queryBuilder()
    {
        \Analog\Analog::log("Sherlock::query()", \Analog\Analog::DEBUG);

        return new \Sherlock\wrappers\QueryWrapper();
    }

    /**
	 * Filter builder, used to return a FilterWrapper through which a Filter component can be selected
     * @return wrappers\FilterWrapper
     */
    public static function filterBuilder()
    {
        \Analog\Analog::log("Sherlock::filter()", \Analog\Analog::DEBUG);

        return new \Sherlock\wrappers\FilterWrapper();
    }

	/**
	 * Facet builder, used to return a FilterWrapper through which a Filter component can be selected
	 * @return wrappers\FacetWrapper
	 */
	public static function facetBuilder()
	{
		\Analog\Analog::log("Sherlock::facetBuilder()", \Analog\Analog::DEBUG);

		return new \Sherlock\wrappers\FacetWrapper();
	}

    /**
	 * Index builder, used to return a IndexWrapper through which an Index component can be selected
     * @return wrappers\IndexSettingsWrapper
     */
    public static function indexSettingsBuilder()
    {
        \Analog\Analog::log("Sherlock::indexSettings()", \Analog\Analog::DEBUG);

        return new \Sherlock\wrappers\IndexSettingsWrapper();
    }

    /**
	 * Mapping builder, used to return a MappingWrapper through which a Mapping component can be selected
     * @param  null|string                     $type
     * @return wrappers\MappingPropertyWrapper
     */
    public static function mappingBuilder($type = null)
    {
        \Analog\Analog::log("Sherlock::mappingProperty()", \Analog\Analog::DEBUG);

        return new \Sherlock\wrappers\MappingPropertyWrapper($type);
    }

	public static function sortBuilder()
	{
		\Analog\Analog::log("Sherlock::sortBuilder()", \Analog\Analog::DEBUG);
		return new SortWrapper();
	}

    /**
	 * Used to obtain a SearchRequest object, allows querying the cluster with searches
     * @return requests\SearchRequest
     */
    public function search()
    {
        \Analog\Analog::log("Sherlock->search()", \Analog\Analog::DEBUG);

        return new \Sherlock\requests\SearchRequest($this->settings['event.dispatcher']);
    }

	/**
	 * Used to return an IndexDocumentRequest object, allows adding a doc to the index
	 * @return requests\IndexDocumentRequest
	 */
	public function document()
    {
        \Analog\Analog::log("Sherlock->indexDocument()", \Analog\Analog::DEBUG);

        return new \Sherlock\requests\IndexDocumentRequest($this->settings['event.dispatcher']);
    }

    /**
     * @param  string                $index     Index to operate on
     * @param  string                $index,... Index to operate on
     * @return requests\IndexRequest
     */
    public function index($index = null)
    {
        $args = func_get_args();
        $index = array();
        foreach ($args as $arg) {
            $index[] = $arg;
        }

        \Analog\Analog::log("Sherlock->index()", \Analog\Analog::DEBUG);


        return new \Sherlock\requests\IndexRequest($this->settings['event.dispatcher'], $index);
    }

    /**
	 * Autodetects various properties of the cluster and indices
     */
    public function autodetect()
    {
        Analog::log("Start autodetect.", Analog::DEBUG);

        //If we have nodes and are supposed to detect cluster settings/configuration
        if ($this->settings['cluster.autodetect'] == true) {
            $this->settings['cluster']->autodetect();
        }
    }


    /**
     * Add a new node to the ES cluster
     * @param  string                                     $host server host address (either IP or domain)
     * @param  int                                        $port ElasticSearch port (defaults to 9200)
     * @return \Sherlock\Sherlock
     * @throws common\exceptions\BadMethodCallException
     * @throws common\exceptions\InvalidArgumentException
     */
    public function addNode($host, $port = 9200)
    {
        Analog::log("Sherlock->addNode(): ".print_r(func_get_args(), true), Analog::DEBUG);

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
     * Recursively scans a directory and returns the files
     * @param $dir Path to directory to scan
     * @throws common\exceptions\RuntimeException
     * @throws common\exceptions\BadMethodCallException
     * @return array
     */
    private function directoryScan($dir)
    {
        if (!isset($dir))
             throw new exceptions\BadMethodCallException("Directory path cannot be empty");

        if (!is_readable($dir))
            throw new exceptions\RuntimeException("Directory is not readable.");

        $files = Array();
        $dir = realpath($dir);
        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir,\FilesystemIterator::SKIP_DOTS ));


        foreach ($objects as $entry => $object) {

            $entry = str_replace($dir, '', $entry);

            $entry = ltrim($entry,'\\/');

            $filetype = pathinfo($entry, PATHINFO_EXTENSION);
            if (in_array(strtolower($filetype), $this->settings['templates.extension'])) {
                $fullPath = $entry;
                $entry = rtrim(str_replace($this->settings['templates.extension'],"",$entry), '.');
                $files[$entry] = $fullPath;
            }

        }

        return $files;

    }

	/**
	 * Setup Analog logger
	 */
	private function setupLogging()
    {
        $level = Analog::DEBUG;

        switch ($this->settings['log.level']) {
            case 'debug':
                $level = Analog::DEBUG;
                break;
            case 'info':
                $level = Analog::INFO;
                break;
            case 'notice':
                $level = Analog::NOTICE;
                break;
            case 'warning':
                $level = Analog::WARNING;
                break;
            case 'error':
                $level = Analog::ERROR;
                break;
            case 'critical':
                $level = Analog::CRITICAL;
                break;
            case 'alert':
                $level = Analog::ALERT;
                break;
        }

		//if logging is disabled, use the null Analog logger
		if ($this->settings['log.enabled'] == false)
			$this->settings['log.handler'] = \Analog\Handler\Null::init();
		else
		{
			//if logging is enabled but no handler was specified by the user
			//use the default file logger
			if ($this->settings['log.handler'] === null)
				$this->settings['log.handler'] = \Analog\Handler\Threshold::init (\Analog\Handler\File::init ($this->settings['base'] . $this->settings['log.file']),$level);
		}
        Analog::handler($this->settings['log.handler']);

        Analog::log("--------------------------------------------------------", Analog::ALERT);
        Analog::log("Logging setup at ".date("Y-m-d H:i:s.u"), Analog::INFO);
    }



}
