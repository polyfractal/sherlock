<?php
/**
 * User: zach
 * Date: 6/18/13
 * Time: 12:16 PM
 */

namespace Sherlock\tests\Integration;

use Sherlock\Builder;
use Sherlock\Sherlock;

/**
 * Class SherlockIntegrationTest
 * @package Sherlock\Tests\Integration
 */
class SherlockIntegrationTest
{
    public function test()
    {
        $sherlock = new Sherlock();
        $query = Builder::query()->MatchAll();
        //$t = $sherlock->search()->query($query)->;



    }
}