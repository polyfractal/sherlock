<?php
/**
 * User: Zachary Tong
 * Date: 2/23/13
 * Time: 10:30 AM
 * @package Sherlock\common\exceptions
 */
namespace Sherlock\common\exceptions;

/**
 * BadResponseException - generic error that Guzzle returns
 */
class BadResponseException extends \Guzzle\Http\Exception\BadResponseException implements SherlockException {}
