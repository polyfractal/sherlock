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

    /**
     * @param array $userParameters
     */
    public function __construct($userParameters = array())
    {
        $this->buildDIC($userParameters);

    }


    /**
     * @return SearchFacade
     */
    public function search()
    {
        return $this['search'];
    }


    /**
     * @param array $userParameters
     */
    private function buildDIC($userParameters)
    {
        $this->setDICParams($userParameters);
        $this->populateDIC();

    }


    /**
     * @param array $userParameters
     *
     * @return array
     */
    private function setDICParams($userParameters)
    {
        $defaultParams = $this->getDefaultParams();
        $params        = array_merge($defaultParams, $userParameters);

        foreach ($params as $key => $value) {
            $this[$key] = $value;
        }

    }


    /**
     * @return array
     */
    private function getDefaultParams()
    {
        return array(
            'responseFactoryClass' => 'ResponseFactory'
        );
    }


    private function populateDIC()
    {

        $this['transport'] = function($dicParams) {
            return new \Elasticsearch\Client();
        };

        $this['responseFactory'] = function($dicParams) {
            return new $dicParams['responseFactoryClass']();
        };

        $this['search'] = function($dicParams) {
            return new SearchFacade($dicParams['transport'], $dicParams['responseFactory']);
        };
    }

}
