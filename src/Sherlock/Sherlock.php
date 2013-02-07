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

class Sherlock
{



	private $nodes = array();
	protected $settings;
	protected $templates = array();



	public function __construct($userSettings = array())
	{
		$this->settings = array_merge(static::getDefaultSettings(), $userSettings);
		$this->loadTemplates();
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
	 * @throws MissingValue
	 */
	public function addNode($host, $port = 9200)
	{
		if (!isset($host))
			throw new MissingValue("A server address must be provided when adding a node.");

		if(!is_numeric($port))
			throw new MissingValue("Port argument must be a number");

		$this->settings['nodes'][] = array($host, $port);
	}
	public function setNodes($nodes)
	{
		$this->nodes = $nodes;
	}

	public function getNodes()
	{
		return $this->nodes;
	}




	/**
	 * Recursively scans a directory and returns the files
	 * @param $dir Path to directory to scan
	 * @throws MissingValue
	 * @throws DirectoryNotReadable
	 * @return array
	 */
	private function directoryScan($dir) {
		if (!isset($dir))
			 throw new MissingValue("Directory path cannot be empty");

		if (!is_readable($dir))
			throw new DirectoryNotReadable("Directory is not readable.");

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

