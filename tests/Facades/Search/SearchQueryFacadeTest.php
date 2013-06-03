<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 2:40 PM
 */

namespace Sherlock\tests\Facades\Search;


use Sherlock\Facades\Search\SearchQueryFacade;


/**
 * Class SearchQueryFacadeTest
 * @package Sherlock\tests\Facades\Search
 */
class SearchQueryFacadeTest extends \PHPUnit_Framework_TestCase
{

    public function testQueryAndConstructor()
    {
        $mockTransport = $this->getMockBuilder('Sherlock\common\Transport')->disableOriginalConstructor()->getMock();
        $mockTransport->expects($this->once())
            ->method('search')
            ->with(
                $this->equalTo(array('term' => 'value')),
                $this->equalTo(''),
                $this->equalTo(''),
                $this->isEmpty()
            );
        $mockQuery = $this->getMock('Sherlock\components\queries\Term');
        $mockQuery->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('term' => 'value')));

        $mockResponseFactory = $this->getMock('Sherlock\Responses\ResponseFactory');
        $mockResponseFactory->expects($this->once())
            ->method('getSearchResponse');

        $search = new SearchQueryFacade($mockTransport, $mockResponseFactory, $mockQuery);
        $search->get();
    }

    public function testIndex()
    {
        $mockTransport = $this->getMockBuilder('Sherlock\common\Transport')->disableOriginalConstructor()->getMock();
        $mockTransport->expects($this->once())
        ->method('search')
        ->with(
                $this->equalTo(array('term' => 'value')),
                $this->equalTo('testIndex'),
                $this->equalTo(''),
                $this->isEmpty()
            );
        $mockQuery = $this->getMock('Sherlock\components\queries\Term');
        $mockQuery->expects($this->once())
        ->method('toArray')
        ->will($this->returnValue(array('term' => 'value')));

        $mockResponseFactory = $this->getMock('Sherlock\Responses\ResponseFactory');
        $mockResponseFactory->expects($this->once())
        ->method('getSearchResponse');

        $search = new SearchQueryFacade($mockTransport, $mockResponseFactory, $mockQuery);
        $search->index('testIndex');

        $search->get();
    }

    public function testMultipleIndex()
    {
        $mockTransport = $this->getMockBuilder('Sherlock\common\Transport')->disableOriginalConstructor()->getMock();
        $mockTransport->expects($this->once())
        ->method('search')
        ->with(
                $this->equalTo(array('term' => 'value')),
                $this->equalTo('testIndex,testIndex2'),
                $this->equalTo(''),
                $this->isEmpty()
            );
        $mockQuery = $this->getMock('Sherlock\components\queries\Term');
        $mockQuery->expects($this->once())
        ->method('toArray')
        ->will($this->returnValue(array('term' => 'value')));

        $mockResponseFactory = $this->getMock('Sherlock\Responses\ResponseFactory');
        $mockResponseFactory->expects($this->once())
        ->method('getSearchResponse');

        $search = new SearchQueryFacade($mockTransport, $mockResponseFactory, $mockQuery);
        $search->index('testIndex')->index('testIndex2');

        $search->get();
    }

    public function testMultipleIndices()
    {
        $mockTransport = $this->getMockBuilder('Sherlock\common\Transport')->disableOriginalConstructor()->getMock();
        $mockTransport->expects($this->once())
        ->method('search')
        ->with(
                $this->equalTo(array('term' => 'value')),
                $this->equalTo('testIndex,testIndex2'),
                $this->equalTo(''),
                $this->isEmpty()
            );
        $mockQuery = $this->getMock('Sherlock\components\queries\Term');
        $mockQuery->expects($this->once())
        ->method('toArray')
        ->will($this->returnValue(array('term' => 'value')));


        $mockResponseFactory = $this->getMock('Sherlock\Responses\ResponseFactory');
        $mockResponseFactory->expects($this->once())
        ->method('getSearchResponse');

        $indices = array('testIndex', 'testIndex2');

        $search = new SearchQueryFacade($mockTransport, $mockResponseFactory, $mockQuery);
        $search->indices($indices);

        $search->get();
    }


    public function testType()
    {
        $mockTransport = $this->getMockBuilder('Sherlock\common\Transport')->disableOriginalConstructor()->getMock();
        $mockTransport->expects($this->once())
        ->method('search')
        ->with(
                $this->equalTo(array('term' => 'value')),
                $this->equalTo(''),
                $this->equalTo('testType'),
                $this->isEmpty()
            );
        $mockQuery = $this->getMock('Sherlock\components\queries\Term');
        $mockQuery->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('term' => 'value')));

        $mockResponseFactory = $this->getMock('Sherlock\Responses\ResponseFactory');
        $mockResponseFactory->expects($this->once())
            ->method('getSearchResponse');

        $search = new SearchQueryFacade($mockTransport, $mockResponseFactory, $mockQuery);
        $search->type('testType');

        $search->get();
    }

    public function testMultipleType()
    {
        $mockTransport = $this->getMockBuilder('Sherlock\common\Transport')->disableOriginalConstructor()->getMock();
        $mockTransport->expects($this->once())
        ->method('search')
        ->with(
                $this->equalTo(array('term' => 'value')),
                $this->equalTo(''),
                $this->equalTo('testType,testType2'),
                $this->isEmpty()
            );
        $mockQuery = $this->getMock('Sherlock\components\queries\Term');
        $mockQuery->expects($this->once())
        ->method('toArray')
        ->will($this->returnValue(array('term' => 'value')));

        $mockResponseFactory = $this->getMock('Sherlock\Responses\ResponseFactory');
        $mockResponseFactory->expects($this->once())
        ->method('getSearchResponse');

        $search = new SearchQueryFacade($mockTransport, $mockResponseFactory, $mockQuery);
        $search->type('testType')->type('testType2');

        $search->get();
    }

    public function testMultipleTypes()
    {
        $mockTransport = $this->getMockBuilder('Sherlock\common\Transport')->disableOriginalConstructor()->getMock();
        $mockTransport->expects($this->once())
        ->method('search')
        ->with(
                $this->equalTo(array('term' => 'value')),
                $this->equalTo(''),
                $this->equalTo('testType,testType2'),
                $this->isEmpty()
            );
        $mockQuery = $this->getMock('Sherlock\components\queries\Term');
        $mockQuery->expects($this->once())
        ->method('toArray')
        ->will($this->returnValue(array('term' => 'value')));

        $mockResponseFactory = $this->getMock('Sherlock\Responses\ResponseFactory');
        $mockResponseFactory->expects($this->once())
        ->method('getSearchResponse');

        $types = array('testType', 'testType2');
        $search = new SearchQueryFacade($mockTransport, $mockResponseFactory, $mockQuery);
        $search->types($types);

        $search->get();
    }


}
