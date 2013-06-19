<?php
/**
 * User: zach
 * Date: 6/19/13
 * Time: 5:33 PM
 */

namespace Sherlock\tests\Facades\Search;

use Sherlock\Facades\Document\DocumentFacade;
use Mockery as m;

/**
 * Class DocumentFacadeTest
 * @package Sherlock\tests\Facades\Search
 */
class DocumentFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testIndexReturnsDocumentComposer()
    {
        $mockDocumentComposer = m::mock('\Sherlock\Composers\Document\DocumentComposer')
                                ->shouldReceive('setFacade')
                                ->once()
                                ->getMock();

        $facade = new DocumentFacade($mockDocumentComposer);
        $composer = $facade->index();

        $this->assertInstanceOf('\Sherlock\Composers\Document\IndexComposer', $composer);
    }

    public function testGetReturnsDocumentComposer()
    {
        $mockDocumentComposer = m::mock('\Sherlock\Composers\Document\DocumentComposer')
                                ->shouldReceive('setFacade')
                                ->once()
                                ->getMock();

        $facade = new DocumentFacade($mockDocumentComposer);
        $composer = $facade->get();

        $this->assertInstanceOf('\Sherlock\Composers\Document\GetComposer', $composer);
    }

    public function testExistsReturnsDocumentComposer()
    {
        $mockDocumentComposer = m::mock('\Sherlock\Composers\Document\DocumentComposer')
                                ->shouldReceive('setFacade')
                                ->once()
                                ->getMock();

        $facade = new DocumentFacade($mockDocumentComposer);
        $composer = $facade->exists();

        $this->assertInstanceOf('\Sherlock\Composers\Document\ExistsComposer', $composer);
    }

    public function testDeleteReturnsDocumentComposer()
    {
        $mockDocumentComposer = m::mock('\Sherlock\Composers\Document\DocumentComposer')
                                ->shouldReceive('setFacade')
                                ->once()
                                ->getMock();

        $facade = new DocumentFacade($mockDocumentComposer);
        $composer = $facade->delete();

        $this->assertInstanceOf('\Sherlock\Composers\Document\DeleteComposer', $composer);
    }

    public function testUpdateReturnsDocumentComposer()
    {
        $mockDocumentComposer = m::mock('\Sherlock\Composers\Document\DocumentComposer')
                                ->shouldReceive('setFacade')
                                ->once()
                                ->getMock();

        $facade = new DocumentFacade($mockDocumentComposer);
        $composer = $facade->update();

        $this->assertInstanceOf('\Sherlock\Composers\Document\UpdateComposer', $composer);
    }
}