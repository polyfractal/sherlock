<?php
/**
 * User: Zachary Tong
 * Date: 2/4/13
 * Time: 10:28 AM
 */

namespace sherlock;


use sherlock\requests;
use sherlock\components;
use sherlock\common\exceptions;
use Guzzle\Http\Client;
use Analog\Analog;

class Sherlock
{

	protected $settings;
	protected $templates = array();




	public function __construct($userSettings = array())
	{


		$this->settings = array_merge(static::getDefaultSettings(), $userSettings);


		//$this->loadTemplates();

		//setup logging
		$this->setupLogging();
		Analog::log("Settings: ".print_r($this->settings, true), Analog::DEBUG);

		$this->autodetect();
	}

	public static function getDefaultSettings()
	{
        return array(
			// Application
			'base' => __DIR__.'/',
			'mode' => 'development',
			'log.level' => 'debug',
			'log.file' => '../sherlock.log',
			'autodetect.cluster' => false
			);
	}

	public static function query()
	{
		\Analog\Analog::log("Sherlock::query()", \Analog\Analog::DEBUG);
		return new \sherlock\requests\QueryWrapper();
	}

	public static function indexSettings()
	{
		\Analog\Analog::log("Sherlock::indexSettings()", \Analog\Analog::DEBUG);
		return new \sherlock\requests\IndexSettingsWrapper();
	}

	/**
	 * @param null|string $type
	 * @return requests\MappingPropertyWrapper
	 */
	public static function mappingProperty($type = null)
	{
		\Analog\Analog::log("Sherlock::mappingProperty()", \Analog\Analog::DEBUG);
		return new \sherlock\requests\MappingPropertyWrapper($type);
	}







	public function search()
	{
		\Analog\Analog::log("Sherlock->search()", \Analog\Analog::DEBUG);
		$randInt = rand(0,count($this->settings['nodes'])-1);
		$randomNode = $this->settings['nodes'][$randInt];
		return new \sherlock\requests\SearchRequest($randomNode);
	}

	/**
	 * @param string $index Index to operate on
	 * @param string $index,... Index to operate on
	 * @return requests\IndexRequest
	 */
	public function index($index = null)
	{
		$args = func_get_args();
		$index = array();
		foreach($args as $arg)
		{
			$index[] = $arg;
		}

		\Analog\Analog::log("Sherlock->index()", \Analog\Analog::DEBUG);
		$randInt = rand(0,count($this->settings['nodes'])-1);
		$randomNode = $this->settings['nodes'][$randInt];
		return new \sherlock\requests\IndexRequest($randomNode, $index);
	}






	/**
	 * @todo refactor cluster autodetection into it's own set of components, etc
	 */
	public function autodetect()
	{
		Analog::log("Start autodetect.", Analog::DEBUG);

		//If we have nodes and are supposed to detect cluster settings/configuration
		if (isset($this->settings['nodes']) && $this->settings['autodetect.cluster'] == true)
		{
			$this->autodetect_parseNodes();
		}
	}


	/**
	 * Add a new node to the ES cluster
	 * @param string $host server host address (either IP or domain)
	 * @param int $port ElasticSearch port (defaults to 9200)
	 * @return \sherlock\Sherlock
	 * @throws common\exceptions\BadMethodCallException
	 * @throws common\exceptions\InvalidArgumentException
	 */
	public function addNode($host, $port = 9200)
	{
		Analog::log("Sherlock->addNode(): ".print_r(func_get_args(), true), Analog::DEBUG);

		if (!isset($host))
			throw new exceptions\BadMethodCallException("A server address must be provided when adding a node.");

		if(!is_numeric($port))
			throw new exceptions\InvalidArgumentException("Port argument must be a number");

		$this->settings['nodes'][] = array('host' => $host, 'port' => $port);
		$this->autodetect();

		return $this;
	}








	/**
	 * Recursively scans a directory and returns the files
	 * @param $dir Path to directory to scan
	 * @throws common\exceptions\RuntimeException
	 * @throws common\exceptions\BadMethodCallException
	 * @return array
	 */
	private function directoryScan($dir) {
		if (!isset($dir))
			 throw new exceptions\BadMethodCallException("Directory path cannot be empty");

		if (!is_readable($dir))
			throw new exceptions\RuntimeException("Directory is not readable.");

		$files = Array();
		$dir = realpath($dir);
		$objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir,\FilesystemIterator::SKIP_DOTS ));


		foreach($objects as $entry => $object){

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

	private function setupLogging()
	{
		$level = Analog::DEBUG;

		switch ($this->settings['log.level'])
		{
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
		Analog::handler(\Analog\Handler\LevelBuffer::init (\Analog\Handler\File::init ($this->settings['base'] . $this->settings['log.file']),$level));
		Analog::log("--------------------------------------------------------", Analog::ALERT);
		Analog::log("Logging setup at ".date("Y-m-d H:i:s.u"), Analog::INFO);
	}

	private function autodetect_parseNodes()
	{
		Analog::log("Autodetecting nodes in cluster...", Analog::DEBUG);
		foreach($this->settings['nodes'] as $node)
		{
			Analog::log("Contacting node: ".print_r($node, true), Analog::DEBUG);
			try {
				$client = new Client('http://'.$node['host'].':'.$node['port']);
				$request = $client->get('/_nodes/http');
				$response = $request->send()->json();

				$this->settings['nodes'] = array();
				foreach($response['nodes'] as $newNode)
				{
					if (!isset($newNode['http_address']))
						continue;

					preg_match('/inet\[\/([0-9\.]+):([0-9]+)\]/i', $newNode['http_address'], $match);

					$tNode = array('host' => $match[1], 'port' => $match[2]);
					$this->settings['nodes'][] = $tNode;
					Analog::log("Autodetected node: ".print_r($tNode, true), Analog::INFO);
				}

				//we have the complete node list, no need to keep hitting nodes
				break;

			} catch (\Guzzle\Http\Exception\BadResponseException $e) {
				//error with this node, continue onto the next one
				Analog::log("Node inaccessible, trying next node in list.", Analog::DEBUG);
			}

		}
	}

}

