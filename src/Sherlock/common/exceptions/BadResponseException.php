<?php
/**
 * User: Zachary Tong
 * Date: 2/23/13
 * Time: 10:30 AM
 */
namespace Sherlock\common\exceptions;

class BadResponseException extends \Guzzle\Http\Exception\BadResponseException implements SherlockException {}
