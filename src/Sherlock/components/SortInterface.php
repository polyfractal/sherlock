<?php
/**
 * User: Zachary Tong
 * Date: 3/7/13
 * Time: 8:09 PM
 */

namespace Sherlock\components;

/**
 * Interface for sort components, always used in conjunction with the BaseComponent class
 */
interface SortInterface
{
    public function toArray();
    public function toJSON();
}
