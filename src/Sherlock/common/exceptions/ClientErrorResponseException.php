<?php
/**
 * User: Zachary Tong
 * Date: 2/23/13
 * Time: 10:27 AM
 * @package Sherlock\common\exceptions
 */
namespace Sherlock\common\exceptions;

/**
 * ClientErrorResponseException - 4xx category of HTTP errors
 */
class ClientErrorResponseException extends \Exception implements SherlockException {}
