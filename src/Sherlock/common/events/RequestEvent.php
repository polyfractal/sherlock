<?php
/**
 * User: Zachary Tong
 * Date: 3/11/13
 * Time: 6:23 AM
 */

namespace Sherlock\common\events;

use Sherlock\requests\Request;
use Symfony\Component\EventDispatcher\Event;

class RequestEvent extends Event
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
