<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 2:40 PM
 */

namespace Sherlock\tests\Facades\Search;

use Sherlock\Facades\Search\SearchFacade;
use Mockery as m;

/**
 * Class SearchFacadeTest
 * @package Sherlock\tests\Facades\SearchWhere
 */
class SearchFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testQueryReturnsComposer()
    {
        $mockTransport = m::mock('\Elasticsearch\Client');
        $mockResponse  = m::mock('\Sherlock\Responses\ResponseFactory');
        $mockQuery     = m::mock('\Sherlock\components\queries\MatchAll')
                         ->shouldReceive('toArray')
                         ->once()
                         ->getMock();

        $facade = new SearchFacade($mockTransport, $mockResponse);
        $composer = $facade->query($mockQuery);

        $this->assertInstanceOf('\Sherlock\Composers\Search\QueryComposer', $composer);
    }

}
