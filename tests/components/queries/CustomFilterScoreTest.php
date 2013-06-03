<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 7:49 AM
 */

namespace Sherlock\tests;

use Sherlock\Sherlock;

class CustomFilterScoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Sherlock\sherlock
     */
    protected $object;


    public function __construct()
    {
    }


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Sherlock();
        $this->object->addNode('localhost', '9200');
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::query
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::filters
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::score_mode
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::max_boost
     * @covers sherlock\Sherlock\requests\SearchRequest::query
     * @covers sherlock\Sherlock\requests\SearchRequest::toJSON
     */
    public function testSingleInlineFilter()
    {
        $req = $this->object->search();
        $req->index("testqueries")->type("test");
        $filter = array(
            'filter' => Sherlock::filterBuilder()->Term()->field("auxillary")->term("auxillary"),
            'boost'  => 2,
        );

        $query = Sherlock::queryBuilder()->CustomFiltersScore()->query(
                     Sherlock::queryBuilder()->Term()->field("auxillary")->term("auxillary")
                 )
                 ->filters($filter)
                 ->score_mode("first")
                 ->max_boost(0.5);


        $req->query($query);

        $data         = $req->toJSON();
        $expectedData = '{"query":{"custom_filters_score":{"query":{"term":{"auxillary":{"value":"auxillary"}}},"filters":[{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2}],"score_mode":"first","max_boost":0.5}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

    }

    /**
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::query
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::filters
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::score_mode
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::max_boost
     * @covers sherlock\Sherlock\requests\SearchRequest::query
     * @covers sherlock\Sherlock\requests\SearchRequest::toJSON
     */
    public function testMultipleInlineFilter()
    {
        $req = $this->object->search();
        $req->index("testqueries")->type("test");
        $filter = array(
            'filter' => Sherlock::filterBuilder()->Term()->field("auxillary")->term("auxillary"),
            'boost'  => 2,
        );

        $query = Sherlock::queryBuilder()->CustomFiltersScore()->query(
                     Sherlock::queryBuilder()->Term()->field("auxillary")->term("auxillary")
                 )
                 ->filters($filter, $filter, $filter)
                 ->score_mode("first")
                 ->max_boost(0.5);


        $req->query($query);

        $data         = $req->toJSON();
        $expectedData = '{"query":{"custom_filters_score":{"query":{"term":{"auxillary":{"value":"auxillary"}}},"filters":[{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2},{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2},{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2}],"score_mode":"first","max_boost":0.5}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

    }

    /**
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::query
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::filters
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::score_mode
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::max_boost
     * @covers sherlock\Sherlock\requests\SearchRequest::query
     * @covers sherlock\Sherlock\requests\SearchRequest::toJSON
     */
    public function testSingleArrayFilter()
    {
        $req = $this->object->search();
        $req->index("testqueries")->type("test");
        $filter = array(
            array(
                'filter' => Sherlock::filterBuilder()->Term()->field("auxillary")->term("auxillary"),
                'boost'  => 2,
            )
        );

        $query = Sherlock::queryBuilder()->CustomFiltersScore()->query(
                     Sherlock::queryBuilder()->Term()->field("auxillary")->term("auxillary")
                 )
                 ->filters($filter)
                 ->score_mode("first")
                 ->max_boost(0.5);


        $req->query($query);

        $data         = $req->toJSON();
        $expectedData = '{"query":{"custom_filters_score":{"query":{"term":{"auxillary":{"value":"auxillary"}}},"filters":[{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2}],"score_mode":"first","max_boost":0.5}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

    }

    /**
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::query
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::filters
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::score_mode
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::max_boost
     * @covers sherlock\Sherlock\requests\SearchRequest::query
     * @covers sherlock\Sherlock\requests\SearchRequest::toJSON
     */
    public function testMultipleArrayFilter()
    {
        $req = $this->object->search();
        $req->index("testqueries")->type("test");
        $filter = array(
            array(
                'filter' => Sherlock::filterBuilder()->Term()->field("auxillary")->term("auxillary"),
                'boost'  => 2,
            ),
            array(
                'filter' => Sherlock::filterBuilder()->Term()->field("auxillary")->term("auxillary"),
                'boost'  => 2,
            ),
            array(
                'filter' => Sherlock::filterBuilder()->Term()->field("auxillary")->term("auxillary"),
                'boost'  => 2,
            ),
        );

        $query = Sherlock::queryBuilder()->CustomFiltersScore()->query(
                     Sherlock::queryBuilder()->Term()->field("auxillary")->term("auxillary")
                 )
                 ->filters($filter)
                 ->score_mode("first")
                 ->max_boost(0.5);


        $req->query($query);

        $data         = $req->toJSON();
        $expectedData = '{"query":{"custom_filters_score":{"query":{"term":{"auxillary":{"value":"auxillary"}}},"filters":[{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2},{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2},{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2}],"score_mode":"first","max_boost":0.5}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

    }

    /**
     * @expectedException \Exception
     *
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::query
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::filters
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::filter
     * @covers sherlock\Sherlock\requests\SearchRequest::query
     * @covers sherlock\Sherlock\requests\SearchRequest::toJSON
     */
    public function testInvalidFilterParam()
    {
        $req = $this->object->search();
        $req->index("testqueries")->type("test");
        $filter = array(
            'filter' => Sherlock::queryBuilder()->Term()->field("auxillary")->term("auxillary"),
            'boost'  => 2,
        );

        $query = Sherlock::queryBuilder()->CustomFiltersScore()->query(
                     Sherlock::queryBuilder()->Term()->field("auxillary")->term("auxillary")
                 )
                 ->filters($filter);


        $req->query($query);

    }


    /**
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::filter
     * @covers sherlock\Sherlock\requests\SearchRequest::query
     * @covers sherlock\Sherlock\requests\SearchRequest::toJSON
     */
    public function testFilter()
    {
        $req = $this->object->search();
        $req->index("testqueries")->type("test");
        $filter = Sherlock::filterBuilder()->Term()->field("auxillary")->term("auxillary");

        $query = Sherlock::queryBuilder()->CustomFiltersScore()->query(
                     Sherlock::queryBuilder()->Term()->field("auxillary")->term("auxillary")
                 )
                 ->filter($filter, 2)
                 ->score_mode("first")
                 ->max_boost(0.5);


        $req->query($query);

        $data         = $req->toJSON();
        $expectedData = '{"query":{"custom_filters_score":{"query":{"term":{"auxillary":{"value":"auxillary"}}},"filters":[{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2}],"score_mode":"first","max_boost":0.5}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

    }

    /**
     * @covers sherlock\Sherlock\components\queries\CustomFiltersScore::filter
     * @covers sherlock\Sherlock\requests\SearchRequest::query
     * @covers sherlock\Sherlock\requests\SearchRequest::toJSON
     */
    public function testMultipleFilter()
    {
        $req = $this->object->search();
        $req->index("testqueries")->type("test");
        $filter = Sherlock::filterBuilder()->Term()->field("auxillary")->term("auxillary");

        $query = Sherlock::queryBuilder()->CustomFiltersScore()->query(
                     Sherlock::queryBuilder()->Term()->field("auxillary")->term("auxillary")
                 )
                 ->filter($filter, 2)
                 ->filter($filter, 3)
                 ->filter($filter, 4)
                 ->score_mode("first")
                 ->max_boost(0.5);


        $req->query($query);

        $data         = $req->toJSON();
        $expectedData = '{"query":{"custom_filters_score":{"query":{"term":{"auxillary":{"value":"auxillary"}}},"filters":[{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":2},{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":3},{"filter":{"term":{"auxillary":"auxillary","_cache":true}},"boost":4}],"score_mode":"first","max_boost":0.5}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

    }
}