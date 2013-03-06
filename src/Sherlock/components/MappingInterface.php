<?php
/**
 * User: Zachary Tong
 * Date: 2/16/13
 * Time: 8:14 PM
 * @package Sherlock\components
 */

namespace Sherlock\components;

/**
 * Interface for Mapping components, always used in conjunction with the BaseComponent class
 */
interface MappingInterface
{
    public function getType();
    public function toArray();
    public function toJSON();
}
