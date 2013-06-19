<?php
/**
 * User: zach
 * Date: 6/19/13
 * Time: 4:39 PM
 */

namespace Sherlock\tests\Composers\Document;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Sherlock\Composers\Document\DeleteComposer;

/**
 * Class ExistsComposerTest
 * @package Sherlock\tests\Composers\Document
 */
class ExistsComposerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }



}