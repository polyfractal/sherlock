<?php
/**
 * User: Zachary Tong
 * Date: 3/10/13
 * Time: 10:36 AM
 */

namespace Sherlock\tests;
use Analog\Analog;
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

    public function testSettings()
    {
        $sherlock = new Sherlock();
        $data = $sherlock->getSherlockSettings();

        //Check default Null handler
        $settings = array();
        $settings['log.enabled'] = false;
        $sherlock = new Sherlock($settings);
        $data = $sherlock->getSherlockSettings();
        $this->assertEquals($settings['log.enabled'], $data['log.enabled']);

        $nullHandler = \Analog\Handler\Null::init();
        $this->assertEquals($nullHandler, $data['log.handler']);

        //Check default File handler
        $settings = array();
        $settings['log.enabled'] = true;
        $sherlock = new Sherlock($settings);
        $data = $sherlock->getSherlockSettings();
        $this->assertEquals($settings['log.enabled'], $data['log.enabled']);

        $fileHandler = \Analog\Handler\Threshold::init (\Analog\Handler\File::init ($data['base'] .$data['log.file']), Analog::ERROR);
        $this->assertEquals($fileHandler, $data['log.handler']);

        //Check default File handler with custom path
        $settings = array();
        $settings['log.enabled'] = true;
        $settings['log.file'] = '../sherlock.log';
        $sherlock = new Sherlock($settings);
        $data = $sherlock->getSherlockSettings();
        $this->assertEquals($settings['log.file'], $data['log.file']);

        $fileHandler = \Analog\Handler\Threshold::init (\Analog\Handler\File::init ($data['base'] .$data['log.file']), Analog::ERROR);
        $this->assertEquals($fileHandler, $data['log.handler']);

        //Check custom handler (syslog)
        $syslogHandler = \Analog\Handler\Syslog::init ('analog', 'user');

        $settings = array();
        $settings['log.enabled'] = true;
        $settings['log.handler'] = $syslogHandler;
        $sherlock = new Sherlock($settings);
        $data = $sherlock->getSherlockSettings();

        $this->assertEquals($syslogHandler, $data['log.handler']);
    }
}
