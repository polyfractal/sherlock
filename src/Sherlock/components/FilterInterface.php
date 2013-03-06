<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 8:45 PM
 * @package Sherlock\components
 */
namespace Sherlock\components;

/**
 * Interface for filter components, always used in conjunction with the BaseComponent class
 */
interface FilterInterface
{
    public function toArray();
    public function toJSON();
}
