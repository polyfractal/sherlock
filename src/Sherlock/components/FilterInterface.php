<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 8:45 PM
 */
namespace Sherlock\components;

interface FilterInterface
{
    public function toArray();
    public function toJSON();
}
