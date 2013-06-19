<?php
/**
 * User: zach
 * Date: 6/19/13
 * Time: 4:42 PM
 */

namespace Sherlock\tests\Composers\Document;

use Elasticsearch\Common\Exceptions\UnexpectedValueException;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use Sherlock\Composers\Document\DeleteComposer;
use Sherlock\Composers\Document\IndexComposer;

/**
 * Class IndexComposerTest
 * @package Sherlock\tests\Composers\Document
 */
class IndexComposerTest extends PHPUnit_Framework_TestCase
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
        $deleteComposer = new IndexComposer($mockDocumentComposer);
        $deleteComposer->consistency('abc');
    }

    public function testValidConsistency()
    {
        $hash['consistency'] = 'one';

        $mockDocumentComposer = m::mock('\Sherlock\Composers\Document\DocumentComposer')
                                ->shouldReceive('enqueueIndex')
                                ->once()
                                ->with($hash)
                                ->getMock();
        $deleteComposer = new IndexComposer($mockDocumentComposer);
        $deleteComposer->consistency('one');
        $deleteComposer->enqueue();
    }

}