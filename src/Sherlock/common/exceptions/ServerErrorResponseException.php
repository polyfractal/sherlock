<?php
/**
 * User: Zachary Tong
 * Date: 2/23/13
 * Time: 10:28 AM
 * @package Sherlock\common\exceptions
 */

namespace Sherlock\common\exceptions;

/**
 * ServerErrorResponseException -  5xx category of http errors
 */
class ServerErrorResponseException extends \Exception implements SherlockException {}
