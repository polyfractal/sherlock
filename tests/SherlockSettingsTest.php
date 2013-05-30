<?php
/**
 * User: Zachary Tong
 * Date: 3/10/13
 * Time: 10:36 AM
 */

namespace Sherlock\tests;

use Sherlock\Sherlock;

class SherlockSettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Sherlock
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }


    public function assertThrowsException($exception_name, $code)
    {
        $e = null;
        try {
            $code();
        } catch (\Exception $e) {
            // No more code, we only want to catch the exception in $e
        }

        $this->assertInstanceOf($exception_name, $e);
    }



}
