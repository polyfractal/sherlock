<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 5:12 PM
 */

namespace sherlock\components;

interface ComponentInterface
{
	public function __call($name, $arguments);
	public function build();
}
