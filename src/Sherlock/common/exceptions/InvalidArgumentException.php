<?php
/**
 * User: Zachary Tong
 * Date: 2/10/13
 * Time: 11:11 AM
 * @package Sherlock\common\exceptions
 */
namespace Sherlock\common\exceptions;

/**
 * InvalidArgumentException - Thrown when there is an arg mismatch (e.g. int instead of string)
 */
class InvalidArgumentException extends \InvalidArgumentException implements SherlockException {}
