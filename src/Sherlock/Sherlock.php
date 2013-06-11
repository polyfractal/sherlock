<?php
/**
 * User: Zachary Tong
 * Date: 2/4/13
 * Time: 10:28 AM
 * @package Sherlock
 * @author  Zachary Tong
 * @version 0.1.2
 */

namespace Sherlock;

use Pimple;
use Sherlock\common\Transport;
use Sherlock\requests;
use Sherlock\common\exceptions;
use Sherlock\responses\ResponseFactory;
use Sherlock\search\facades\SearchFacade;

/**
 * Class Sherlock
 * @package Sherlock
 */
class Sherlock extends Pimple
{


    public function __construct()
    {
        $this->buildDIC();

    }

    public function search()
    {
        return $this['search'];
    }




    /**
     * Add a new node to the ES cluster
     *
     * @param  string                                     $host server host address (either IP or domain)
     * @param  int                                        $port ElasticSearch port (defaults to 9200)
     *
     * @return \Sherlock\Sherlock
     * @throws common\exceptions\BadMethodCallException
     * @throws common\exceptions\InvalidArgumentException
     */
    public function addNode($host, $port = 9200)
    {


        return $this;
    }

    private function buildDIC()
    {
        $this['transport'] = function($dicParams) {
            return new Transport();
        };

        $this['responseFactory'] = function($dicParams) {
            return new ResponseFactory();
        };

        $this['search'] = function($dicParams) {
            return new SearchFacade($dicParams['transport'], $dicParams['responseFactory']);
        };
    }

}
