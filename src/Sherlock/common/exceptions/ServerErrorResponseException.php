<?php
/**
 * User: Zachary Tong
 * Date: 2/23/13
 * Time: 10:28 AM
 */

namespace Sherlock\common\exceptions;

class ServerErrorResponseException extends \Guzzle\Http\Exception\ServerErrorResponseException implements SherlockException {}
