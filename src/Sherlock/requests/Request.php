<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 8:54 AM
 * @package Sherlock\requests
 */

namespace Sherlock\requests;

use Sherlock\common\events\Events;
use Sherlock\common\events\RequestEvent;
use Sherlock\common\exceptions;
use Sherlock\common\tmp\RollingCurl;
use Sherlock\responses\IndexResponse;

use Sherlock\responses\Response;

/**
 * Base class for various requests.
 *
 * Handles generic functionality such as transport.
 */
class Request
{
    protected $dispatcher;

    public $node;

    //required since PHP doesn't allow argument differences between
    //parent and children under Strict

    /**
     * @var BatchCommandInterface
     */
    protected $batch;


    /**
     * @param  \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     *
     * @throws \Sherlock\common\exceptions\BadMethodCallException
     */
    public function __construct($dispatcher)
    {
        if (!isset($dispatcher)) {
                        throw new exceptions\BadMethodCallException("An Event Dispatcher must be injected into all Request objects");
        }

        $this->dispatcher = $dispatcher;
        $this->batch      = new BatchCommand();
    }


    /**
     * Execute the Request, performs on the actual transport layer
     *
     * @throws exceptions\RuntimeException
     * @throws \Sherlock\common\exceptions\BadResponseException
     * @throws \Sherlock\common\exceptions\ClientErrorResponseException
     * @return \Sherlock\responses\Response
     */
    public function execute()
    {
        //construct a requestEvent and dispatch it with the "request.preexecute" event
        //This will, among potentially other things, populate the $node variable with
        //values from Cluster
        $event = new RequestEvent($this);
        $this->dispatcher->dispatch(Events::REQUEST_PREEXECUTE, $event);

        //Make sure the node variable is set correctly after the event
        if (!isset($this->node)) {
                        throw new exceptions\RuntimeException("Request requires a valid, non-empty node");
        }

        if (!isset($this->node['host'])) {
                        throw new exceptions\RuntimeException("Request requires a host to connect to");
        }

        if (!isset($this->node['port'])) {
                        throw new exceptions\RuntimeException("Request requires a port to connect to");
        }

        $path = 'http://' . $this->node['host'] . ':' . $this->node['port'];


        $rolling = new RollingCurl\RollingCurl();
        $rolling->setHeaders(array('Content-Type: application/json'));

        $window  = 10;
        $counter = 0;

        /** @var BatchCommandInterface $batch */
        $batch = $this->batch;

        //prefill our buffer with a full window
        //the rest will be streamed by our callback closure
        foreach ($batch as $request) {

            /** @var CommandInterface $req */
            $req    = $request;
            $action = $req->getAction();

            if ($action == 'put' || $action == 'post') {
                $rolling->$action(
                    $path . $req->getURI(),
                    json_encode($req->getData()),
                    array('Content-Type: application/json')
                );
            } else {
                $rolling->$action($path . $req->getURI());
            }

            if ($counter > $window) {
                break;
            }
        }

        /**
         * @param RollingCurl\Request     $request
         * @param RollingCurl\RollingCurl $rolling
         */
        $callback = function (RollingCurl\Request $request, RollingCurl\RollingCurl $rolling) use ($batch, $path) {

            //a curl handle just finished, advance the iterator one and add to the queue
            //First check to see if there are any left to process (aka valid)
            if ($batch->valid()) {

                //advance
                $batch->next();

                //make sure we haven't hit the end
                if ($batch->valid()) {

                    $data = $batch->current();

                    $action = $data->getAction();

                    if ($action == 'put' || $action == 'post') {
                        $rolling->$action($path . $data->getURI(), json_encode($data->getData()));
                    } else {
                        $rolling->$action($path . $data->getURI());
                    }
                }
            }
        };

        $rolling->setSimultaneousLimit($window);
        $rolling->setCallback($callback);

        $rolling->execute();
        $ret = $rolling->getCompletedRequests();

        $this->batch = new BatchCommand();

        $finalResponse = array();
        foreach ($ret as $response) {
            $finalResponse[] = $this->getReturnResponse($response);
        }

        return $finalResponse;
    }

    /**
     * @param $response
     * @return \Sherlock\responses\Response
     */
    protected function getReturnResponse($response)
    {
        return new Response($response);
    }
}
