<?php
/**
 * User: Zachary Tong
 * Date: 2/4/13
 * Time: 10:28 AM
 */

namespace sherlock;

use sherlock\Template;
use sherlock\Request;
use sherlock\components;
use sherlock\common\exceptions;

class Sherlock
{

	protected $settings;
	protected $templates = array();



	public function __construct($userSettings = array())
	{
		$this->settings = array_merge(static::getDefaultSettings(), $userSettings);
		//$this->loadTemplates();
	}

	public static function getDefaultSettings()
	{
        return array(
			// Application
			'mode' => 'development',
			'debug' => true,
			'templates.path' => '../templates',
			'templates.extension' => array('yml')
			);
	}

	public static function search()
	{
		return new \sherlock\Request\SearchRequest();
	}

	public static function query()
	{
		return new \sherlock\Request\QueryWrapper();
	}







	public function loadTemplates($path = "", $merge = true)
	{
		if($path == "") {
			$path = $this->settings['templates.path'];
		}

		$files = $this->directoryScan($path);
		$newTemplates = array();
		foreach($files as $file => $fullPath){
			$newTemplates[$file] = new \sherlock\Template\Template($path.$fullPath);
		}

		if ($merge == true)
			$this->templates = array_merge($this->templates, $newTemplates);
		else
			$this->templates = $newTemplates;

	}

	/**
	 * @param $key
	 * @return \sherlock\Template\Template
	 */
	public function getTemplate($key)
	{
		return $this->templates[$key];
	}


	/**
	 * Add a new node to the ES cluster
	 * @param string $host server host address (either IP or domain)
	 * @param int $port ElasticSearch port (defaults to 9200)
	 * @throws common\exceptions\BadMethodCallException
	 * @throws common\exceptions\InvalidArgumentException
	 */
	public function addNode($host, $port = 9200)
	{
		if (!isset($host))
			throw new exceptions\BadMethodCallException("A server address must be provided when adding a node.");

		if(!is_numeric($port))
			throw new exceptions\InvalidArgumentException("Port argument must be a number");

		$this->settings['nodes'][] = array($host, $port);
	}
	public function setNodes($nodes)
	{
		$this->settings['nodes'] = $nodes;
	}

	public function getNodes()
	{
		return $this->settings['nodes'];
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



}

