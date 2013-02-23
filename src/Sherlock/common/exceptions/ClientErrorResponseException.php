<?php
/**
 * User: Zachary Tong
 * Date: 2/23/13
 * Time: 10:27 AM
 */
namespace Sherlock\common\exceptions;

class ClientErrorResponseException extends \Guzzle\Http\Exception\ClientErrorResponseException implements SherlockException {}
