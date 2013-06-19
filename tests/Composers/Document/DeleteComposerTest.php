<?php
/**
 * User: zach
 * Date: 6/19/13
 * Time: 3:58 PM
 */

namespace Sherlock\tests\Composers\Document;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Sherlock\Composers\Document\DeleteComposer;

/**
 * Class DeleteComposerTest
 * @package Sherlock\tests\Composers\Document
 */
class DeleteComposerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }


    /**
     * @expectedException UnexpectedValueException
     */
    public function testIllegalConsistency()
    {
        $mockDocumentComposer = m::mock('\Sherlock\Composers\Document\DocumentComposer');
        $deleteComposer = new DeleteComposer($mockDocumentComposer);
        $deleteComposer->consistency('abc');
    }

    public function testValidConsistency()
    {
        $hash['consistency'] = 'one';

        $mockDocumentComposer = m::mock('\Sherlock\Composers\Document\DocumentComposer')
                                ->shouldReceive('enqueueDelete')
                                ->once()
                                ->with($hash)
                                ->getMock();
        $deleteComposer = new DeleteComposer($mockDocumentComposer);
        $deleteComposer->consistency('one');
        $deleteComposer->enqueue();
    }

}