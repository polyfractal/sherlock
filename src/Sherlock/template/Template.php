<?php
/**
 * User: Zachary Tong
 * Date: 2/4/13
 * Time: 3:22 PM
 */

namespace sherlock\Template;

use Symfony\Component\Yaml\Yaml;



class Template
{
	protected $path;
	protected $yaml;
	protected $parsedYaml;

	public function __construct($path = "")
	{
		$parsedYaml = new \ArrayObject();
		$this->setPath($path);
		if ($this->path != "") {

			$this->load($this->path);
		}
	}

	/**
	 * Loads a yaml template and parses into an array
	 * @param string $path
	 * @throws FileNotReadableException
	 */
	public function load($path = "")
	{
		echo "Load: ".$path."\r\n";
		echo "CWD: ".getcwd()."\r\n";
		if ($path != "")
			$this->setPath($path);

		if($this->path == "")
			throw new FileNotReadableException("Template Path must be set");

		if (!is_readable($this->path))
			throw new FileNotReadableException("File is not readable or does not exist.");

		$this->yaml = file_get_contents($this->path);
		$this->parsedYaml = Yaml::parse($this->yaml);

	}

	public function setPath($path)
	{
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return mixed array of parsed yaml
	 * @throws NoTemplateLoadedException
	 */
	public function getTemplate()
	{
		if (!is_array($this->parsedYaml))
			throw new NoTemplateLoadedException("A template must be loaded before it can be returned.");

		return $this->parsedYaml;
	}


}




interface Exception {}

class FileNotReadableException
	extends \RuntimeException
	implements Exception
{}

class NoTemplateLoadedException
	extends \RuntimeException
	implements Exception
{}