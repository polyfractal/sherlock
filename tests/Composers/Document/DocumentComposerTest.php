<?php
/**
 * User: zach
 * Date: 6/19/13
 * Time: 4:46 PM
 */

namespace Sherlock\tests\Composers\Document;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\Composers\Document\DocumentComposer;

/**
 * Class DocumentComposerTest
 * @package Sherlock\tests\Composers\Document
 */
class DocumentComposerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testEmptyRequestReturnsEmptyResponse()
    {
        $mockTransport = m::mock('\Elasticsearch\Client');
        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory');

        $documentComposer = new DocumentComposer($mockTransport, $mockResponse);
        $responses = $documentComposer->execute();

        $this->assertEmpty($responses);
    }


    /**
     * @expectedException RuntimeException
     */
    public function testEnqueuedEmptyRequestThenExecutedThrowsException()
    {
        $mockTransport = m::mock('\Elasticsearch\Client');
        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory');

        $documentComposer = new DocumentComposer($mockTransport, $mockResponse);
        $responses = $documentComposer->enqueueDelete(null)->index()->execute();

        $this->assertEmpty($responses);
    }


    /**
     * @expectedException RuntimeException
     */
    public function testEnqueueWithoutSettingFacadeThrowsException()
    {
        $mockTransport = m::mock('\Elasticsearch\Client');
        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory');

        $documentComposer = new DocumentComposer($mockTransport, $mockResponse);
        $facade = $documentComposer->enqueueDelete(array('abc'));
    }


    public function testEnqueueReturnsFacade()
    {
        $mockTransport = m::mock('\Elasticsearch\Client');
        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory');
        $mockFacade    = m::mock('\Sherlock\Facades\Document\DocumentFacade');

        $documentComposer = new DocumentComposer($mockTransport, $mockResponse);
        $documentComposer->setFacade($mockFacade);

        $methods = array(
            'enqueueDelete',
            'enqueueGet',
            'enqueueIndex',
            'enqueueExists'
        );

        foreach ($methods as $method) {
            $facade = null;
            $facade = $documentComposer->$method(array('abc'));
            $this->assertInstanceOf('\Sherlock\Facades\Document\DocumentFacade', $facade);
        }

    }

    public function testEnqueueIndexThenExecute()
    {
        $hash       = array('abc');
        $returnHash = array('xyz');

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('index')
                         ->once()
                         ->with($hash)
                         ->andReturn($returnHash)
                         ->getMock();
        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory');
        $mockFacade    = m::mock('\Sherlock\Facades\Document\DocumentFacade');

        $documentComposer = new DocumentComposer($mockTransport, $mockResponse);
        $documentComposer->setFacade($mockFacade);

        $documentComposer->enqueueIndex($hash);
        $response = $documentComposer->execute();
        $this->assertEquals(array($returnHash), $response);

    }

    public function testEnqueueGetThenExecute()
    {
        $hash       = array('abc');
        $returnHash = array('xyz');

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('get')
                         ->once()
                         ->with($hash)
                         ->andReturn($returnHash)
                         ->getMock();
        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory');
        $mockFacade    = m::mock('\Sherlock\Facades\Document\DocumentFacade');

        $documentComposer = new DocumentComposer($mockTransport, $mockResponse);
        $documentComposer->setFacade($mockFacade);

        $documentComposer->enqueueGet($hash);
        $response = $documentComposer->execute();
        $this->assertEquals(array($returnHash), $response);

    }

    public function testEnqueueExistsThenExecute()
    {
        $hash       = array('abc');
        $returnHash = array('xyz');

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('exists')
                         ->once()
                         ->with($hash)
                         ->andReturn($returnHash)
                         ->getMock();
        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory');
        $mockFacade    = m::mock('\Sherlock\Facades\Document\DocumentFacade');

        $documentComposer = new DocumentComposer($mockTransport, $mockResponse);
        $documentComposer->setFacade($mockFacade);

        $documentComposer->enqueueExists($hash);
        $response = $documentComposer->execute();
        $this->assertEquals(array($returnHash), $response);

    }

    public function testEnqueueDeleteThenExecute()
    {
        $hash       = array('abc');
        $returnHash = array('xyz');

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('delete')
                         ->once()
                         ->with($hash)
                         ->andReturn($returnHash)
                         ->getMock();
        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory');
        $mockFacade    = m::mock('\Sherlock\Facades\Document\DocumentFacade');

        $documentComposer = new DocumentComposer($mockTransport, $mockResponse);
        $documentComposer->setFacade($mockFacade);

        $documentComposer->enqueueDelete($hash);
        $response = $documentComposer->execute();
        $this->assertEquals(array($returnHash), $response);

    }

}