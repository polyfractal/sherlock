<?php
/**
 * User: zach
 * Date: 3/22/13
 * Time: 8:00 AM
 */

namespace Sherlock\requests;

/**
 * Class CommandInterface
 */
interface CommandInterface
{
    /**
     * @return string
     */
    public function getURI();

    /**
     * @return string
     */
    public function getAction();

    /**
     * @return string
     */
    public function getData();
}
