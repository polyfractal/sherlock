<?php
/**
 * User: Zachary Tong
 * Date: 3/11/13
 * Time: 6:26 AM
 */

namespace Sherlock\common\events;

/**
 * Class Events - Reference class for all events in dispatch system
 * @package Sherlock\common\events
 */
final class Events
{
    /**
     * request.preexecute event is thrown just prior to the event being executed.
     * Perfect for injecting a node address
     *
     * The event listener receives an \Sherlock\common\events\RequestEvent
     *
     * @var string
     */
    const REQUEST_PREEXECUTE = 'request.preexecute';
}
