<?php
/**
 * User: zach
 * Date: 6/18/13
 * Time: 3:14 PM
 */

use Mockery as m;
use Sherlock\Composers\Search\QueryComposer;

/**
 * Class QueryComposerTest
 */
class QueryComposerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testConstructorNoQuery()
    {
        $hash['body']['query']['match_all'] = array();

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('search')
                         ->once()
                         ->with($hash)
                         ->getMock();

        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory')
                         ->shouldReceive('getSearchResponse')
                         ->once()
                         ->getMock();

        $queryComposer = new QueryComposer($mockTransport, $mockResponse);
        $queryComposer->execute();
    }

    public function testConstructorWithQueryObject()
    {
        $hash['body']['query']['term'] = array('field' => 'value');

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('search')
                         ->once()
                         ->with($hash)
                         ->getMock();

        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory')
                         ->shouldReceive('getSearchResponse')
                         ->once()
                         ->getMock();

        $mockQuery = m::mock('\Sherlock\components\queries\MatchAll')
                         ->shouldReceive('toArray')
                         ->once()
                         ->andReturn(array('term' => array('field' => 'value')))
                         ->getMock();

        $queryComposer = new QueryComposer($mockTransport, $mockResponse, $mockQuery);
        $queryComposer->execute();
    }

    public function testConstructorWithQueryString()
    {
        $hash['q'] = 'abc';

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('search')
                         ->once()
                         ->with($hash)
                         ->getMock();

        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory')
                         ->shouldReceive('getSearchResponse')
                         ->once()
                         ->getMock();


        $queryComposer = new QueryComposer($mockTransport, $mockResponse, 'abc');
        $queryComposer->execute();
    }

    public function testConstructorOneIndexViaIndex()
    {
        $hash['body']['query']['match_all'] = array();
        $hash['index'] = 'index';

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('search')
                         ->once()
                         ->with($hash)
                         ->getMock();

        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory')
                         ->shouldReceive('getSearchResponse')
                         ->once()
                         ->getMock();

        $queryComposer = new QueryComposer($mockTransport, $mockResponse);
        $queryComposer->index('index');
        $queryComposer->execute();
    }

    public function testConstructorTwoIndexViaIndex()
    {
        $hash['body']['query']['match_all'] = array();
        $hash['index'] = 'index,index';

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('search')
                         ->once()
                         ->with($hash)
                         ->getMock();

        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory')
                         ->shouldReceive('getSearchResponse')
                         ->once()
                         ->getMock();

        $queryComposer = new QueryComposer($mockTransport, $mockResponse);
        $queryComposer->index('index');
        $queryComposer->index('index');
        $queryComposer->execute();
    }

    public function testConstructorTwoIndexViaIndices()
    {
        $hash['body']['query']['match_all'] = array();
        $hash['index'] = 'index,index';

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('search')
                         ->once()
                         ->with($hash)
                         ->getMock();

        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory')
                         ->shouldReceive('getSearchResponse')
                         ->once()
                         ->getMock();

        $queryComposer = new QueryComposer($mockTransport, $mockResponse);
        $queryComposer->indices(array('index', 'index'));
        $queryComposer->execute();
    }

    public function testConstructorOneTypeViaType()
    {
        $hash['body']['query']['match_all'] = array();
        $hash['type'] = 'type';

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('search')
                         ->once()
                         ->with($hash)
                         ->getMock();

        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory')
                         ->shouldReceive('getSearchResponse')
                         ->once()
                         ->getMock();

        $queryComposer = new QueryComposer($mockTransport, $mockResponse);
        $queryComposer->type('type');
        $queryComposer->execute();
    }

    public function testConstructorTwoTypeViaType()
    {
        $hash['body']['query']['match_all'] = array();
        $hash['type'] = 'type,type';

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('search')
                         ->once()
                         ->with($hash)
                         ->getMock();

        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory')
                         ->shouldReceive('getSearchResponse')
                         ->once()
                         ->getMock();

        $queryComposer = new QueryComposer($mockTransport, $mockResponse);
        $queryComposer->type('type');
        $queryComposer->type('type');
        $queryComposer->execute();
    }

    public function testConstructorTwoTypesViaTypes()
    {
        $hash['body']['query']['match_all'] = array();
        $hash['type'] = 'type,type';

        $mockTransport = m::mock('\Elasticsearch\Client')
                         ->shouldReceive('search')
                         ->once()
                         ->with($hash)
                         ->getMock();

        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory')
                         ->shouldReceive('getSearchResponse')
                         ->once()
                         ->getMock();

        $queryComposer = new QueryComposer($mockTransport, $mockResponse);
        $queryComposer->types(array('type', 'type'));
        $queryComposer->execute();
    }
}