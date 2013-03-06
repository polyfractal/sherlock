<?php
/**
 * User: Zachary Tong
 * Date: 2/10/13
 * Time: 11:11 AM
 * @package Sherlock\common\exceptions
 */
namespace Sherlock\common\exceptions;

/**
 * BadMethodCallException - used to denote problems with a method call (wrong or otherwise incorrect arguments)
 */
class BadMethodCallException extends \BadMethodCallException implements SherlockException {}
