<?php
/**
 * User: zach
 * Date: 3/22/13
 * Time: 7:09 AM
 */

namespace Sherlock\requests;

/**
 * Class BatchCommandInterface
 */
interface BatchCommandInterface extends \Iterator
{
    /**
     * Clear all commands in the BatchCommands buffer.  This is called after a
     * batch set of commands have fully finished processing, to prevent
     * future batches from performing "stale" commands
     *
     * @return void
     */
    public function clearCommands();
}
