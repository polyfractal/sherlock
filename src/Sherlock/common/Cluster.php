<?php
/**
 * User: Zachary Tong
 * Date: 3/10/13
 * Time: 11:23 AM
 */

namespace Sherlock\common;

/**
 * Class Cluster - provides functionality to deal with cluster state
 * @package Sherlock\common
 */
use Analog\Analog;
use Guzzle\Http\Client;
use Sherlock\common\events\RequestEvent;
use Sherlock\common\exceptions\RuntimeException;

class Cluster
{
    private $nodes = array();
    private $dispatcher;

    /**
     * @param $dispatcher
     */
    public function __construct($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param  string                              $host
     * @param  int                                 $port
     * @param  bool                                $autodetect
     * @throws exceptions\BadMethodCallException
     * @throws exceptions\InvalidArgumentException
     */
    public function addNode($host, $port, $autodetect = true)
    {
        if (!isset($host))
            throw new exceptions\BadMethodCallException("A server address must be provided when adding a node.");

        if(!is_numeric($port))
            throw new exceptions\InvalidArgumentException("Port argument must be a number");

        $this->nodes[$host] = array('host' => $host, 'port' => $port);
    }

    /**
     * Autodect various cluster properties
     */
    public function autodetect()
    {
        $this->autodetect_parseNodes();
    }

    /**
     * Triggered just prior to a request being executed
     * Inject a random node into the Request object
     * @param  RequestEvent                $event
     * @throws exceptions\RuntimeException
     */
    public function onRequestExecute(RequestEvent $event)
    {
        Analog::debug("Cluster->onRequestExecute()");
        $request = $event->getRequest();

        //Make sure we have some nodes to choose from
        if (count($this->nodes) === 0) {
            Analog::error("No nodes in cluster, request failed");
            throw new RuntimeException("No nodes in cluster, request failed");
        }

        //Choose a random node
        $request->node = $this->nodes[array_rand($this->nodes)];

    }

    /**
     * Autodetect the nodes in this cluster through Cluster State API
     */
    private function autodetect_parseNodes()
    {
        Analog::log("Autodetecting nodes in cluster...", Analog::DEBUG);
        foreach ($this->nodes as $node) {
            Analog::log("Contacting node: ".print_r($node, true), Analog::DEBUG);

            try {
                $client = new Client('http://'.$node['host'].':'.$node['port']);
                $request = $client->get('/_nodes/http');
                $response = $request->send()->json();

                foreach ($response['nodes'] as $newNode) {

                    //we don't want http-inaccessible nodes
                    if (!isset($newNode['http_address']))
                        continue;

                    preg_match('/inet\[\/([0-9\.]+):([0-9]+)\]/i', $newNode['http_address'], $match);

                    $tNode = array('host' => $match[1], 'port' => $match[2]);

                    //use host as key so that we don't add duplicates
                    $this->nodes[$match[1]] = $tNode;

                    Analog::log("Autodetected node: ".print_r($tNode, true), Analog::INFO);
                }

                //we have the complete node list, no need to keep checking
                break;

            } catch (\Guzzle\Http\Exception\BadResponseException $e) {
                //error with this node, continue onto the next one
                Analog::log("Node inaccessible, trying next node in list.", Analog::DEBUG);
            }
        }
    }
}
