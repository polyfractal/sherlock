<?php
/**
 * User: Zachary Tong
 * Date: 2/16/13
 * Time: 8:14 PM
 */

namespace sherlock\components;

interface MappingInterface
{
	public function getType();
	public function toArray();
	public function toJSON();
}
