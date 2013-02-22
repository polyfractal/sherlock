<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 8:39 PM
 */
namespace Sherlock\components;

interface QueryInterface
{
    public function toArray();
    public function toJSON();
}
