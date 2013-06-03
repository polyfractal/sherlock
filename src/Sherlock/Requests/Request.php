<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 8:54 AM
 * @package Sherlock\Requests
 */

namespace Sherlock\requests;



use Sherlock\common\Transport;
use Sherlock\responses\ResponseFactory;


abstract class Request
{
    /** @var  Transport */
    protected $transport;

    /** @var  ResponseFactory */
    protected $responseFactory;

    public function __construct(Transport $transport, ResponseFactory $responseFactory)
    {
        $this->transport       = $transport;
        $this->responseFactory = $responseFactory;
    }

}
