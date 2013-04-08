<?php
/**
 * User: Zachary Tong
 * Date: 3/14/13
 * Time: 6:57 AM
 */

namespace Sherlock\tests;
use Sherlock\Sherlock;

class FacetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var sherlock
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
        $this->object = new Sherlock;
        $this->object->addNode('localhost', '9200');
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

    /**
      * @covers sherlock\Sherlock\components\facets\TermsFacet::fields
     * @covers sherlock\Sherlock\components\facets\TermsFacet::facetname
     * @covers sherlock\Sherlock\components\facets\TermsFacet::exclude
     * @covers sherlock\Sherlock\components\facets\TermsFacet::order
     * @covers sherlock\Sherlock\components\facets\TermsFacet::regex
     * @covers sherlock\Sherlock\components\facets\TermsFacet::regex_flags
     * @covers sherlock\Sherlock\components\facets\TermsFacet::script
     * @covers sherlock\Sherlock\components\facets\TermsFacet::script_field
     * @covers sherlock\Sherlock\requests\SearchRequest::facet
      */
    public function testTermsFacet()
    {
        $req = $this->object->search();
        $req->index("testfacets")->type("test");
        $query = Sherlock::queryBuilder()->MatchAll();
        $req->query($query);

        //no parameter test, should throw an exception because Fields is not set
        $facet = Sherlock::facetBuilder()->Terms();
        $this->assertThrowsException('\Sherlock\common\exceptions\RuntimeException', function () use ($facet) {
            $data = $facet->toJSON();
        });

        //Set Fields, but not facetname - they should be the same
        $facet = Sherlock::facetBuilder()->Terms()->fields("testfield");
        $req->facets($facet);

        $data = $req->toJSON();
        $expectedData = '{"query":{"match_all":{"boost":1}},"facets":{"testfield":{"terms":{"fields":["testfield"],"order":"count","all_terms":false,"size":null,"exclude":null,"regex":null,"regex_flags":null,"script":null,"script_field":null,"params":null,"lang":null},"facet_filter":null}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

        //Set both fields and facetname
        $facet = Sherlock::facetBuilder()->Terms()->fields("testfield")->facetname("testfield1");
        $req->facets($facet);

        $data = $req->toJSON();
        $expectedData = '{"query":{"match_all":{"boost":1}},"facets":{"testfield1":{"terms":{"fields":["testfield"],"order":"count","all_terms":false,"size":null,"exclude":null,"regex":null,"regex_flags":null,"script":null,"script_field":null,"params":null,"lang":null},"facet_filter":null}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

        //Set multiple fields (arguments), make sure facetname stays as the first
        $facet = Sherlock::facetBuilder()->Terms()->fields("testfield", "testfield1", "testfield2");
        $req->facets($facet);

        $data = $req->toJSON();
        $expectedData = '{"query":{"match_all":{"boost":1}},"facets":{"testfield":{"terms":{"fields":["testfield","testfield1","testfield2"],"order":"count","all_terms":false,"size":null,"exclude":null,"regex":null,"regex_flags":null,"script":null,"script_field":null,"params":null,"lang":null},"facet_filter":null}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

        //Set multiple fields (array) , make sure facetname stays as the first
        $facet = Sherlock::facetBuilder()->Terms()->fields(array("testfield", "testfield1", "testfield2"));
        $req->facets($facet);

        $data = $req->toJSON();
        $expectedData = '{"query":{"match_all":{"boost":1}},"facets":{"testfield":{"terms":{"fields":["testfield","testfield1","testfield2"],"order":"count","all_terms":false,"size":null,"exclude":null,"regex":null,"regex_flags":null,"script":null,"script_field":null,"params":null,"lang":null},"facet_filter":null}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

        //Set all fields just to make sure they wrok
        $facet = Sherlock::facetBuilder()->Terms()->fields("testfield")->facetname("testfield1")
                                                ->all_terms(true)
                                                ->exclude(array("term1", "term2"))
                                                ->order('count')
                                                ->regex("/./")
                                                ->regex_flags("DOTALL")
                                                ->script("_score")
                                                ->script_field("_source.testfield");
                                                $req->facets($facet);

        $data = $req->toJSON();
        $expectedData = '{"query":{"match_all":{"boost":1}},"facets":{"testfield1":{"terms":{"fields":["testfield"],"order":"count","all_terms":true,"size":null,"exclude":["term1","term2"],"regex":"\/.\/","regex_flags":"DOTALL","script":"_score","script_field":"_source.testfield","params":null,"lang":null},"facet_filter":null}}}';
        $this->assertEquals($expectedData, $data);

        $resp = $req->execute();

    }

}
