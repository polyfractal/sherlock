<?php
/**
 * User: Zachary Tong
 * Date: 3/14/13
 * Time: 6:54 AM
 */

namespace Sherlock\wrappers;

/**
 * Class FacetWrapper
 * @package Sherlock\wrappers
 *
 *
 * @method \Sherlock\components\facets\Terms Terms() Terms()

 */
class FacetWrapper
{
	/**
	 * @var \Sherlock\components\FacetInterface
	 */
	protected $facet;

	/**
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments)
	{
		$class = '\\Sherlock\\components\\facets\\'.$name;

		if (count($arguments) > 0)
			$this->facet =  new $class($arguments[0]);
		else
			$this->facet =  new $class();

		return $this->facet;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->facet;
	}

}
