<?php
/**
 * User: Zachary Tong
 * Date: 2/6/13
 * Time: 8:54 AM
 * @package Sherlock\requests
 */

namespace Sherlock\requests;
use Sherlock\common\exceptions;
use Sherlock\responses\IndexResponse;
use Analog\Analog;
use Guzzle\Http\Client;

/**
 * Base class for various requests.
 *
 * Handles generic functionality such as transport.
 */
class Request
{
    protected $node;

    //required since PHP doesn't allow argument differences between
    //parent and children under Strict

	/*
	 * @var string
	 */
    protected $_uri;

	/*
	 * @var string
	 */
    protected $_data;

	/*
	 * @var string
	 */
    protected $_action;

	/**
	 * @param $node
	 * @throws \Sherlock\common\exceptions\BadMethodCallException
	 */
	public function __construct($node)
    {
        if (!isset($node)) {
            \Analog\Analog::log("A list of nodes must be provided for each Request", \Analog\Analog::ERROR);
            throw new exceptions\BadMethodCallException("A list of nodes must be provided for each Request");
        }


        if (!is_array($node)) {
            \Analog\Analog::log("Argument nodes must be an array.", \Analog\Analog::ERROR);
            throw new exceptions\BadMethodCallException("Argument nodes must be an array.");
        }

        $this->node = $node;
    }

	/**
	 * Execute the Request, performs on the actual transport layer
	 *
	 * @throws \Sherlock\common\exceptions\RuntimeException
	 * @throws \Sherlock\common\exceptions\BadResponseException
	 * @throws \Sherlock\common\exceptions\ClientErrorResponseException
	 * @return \Sherlock\responses\Response
	 */
    public function execute()
    {
        $reflector = new \ReflectionClass(get_class($this));
        $class = $reflector->getShortName();

        \Analog\Analog::log("Request->execute()", \Analog\Analog::DEBUG);

        if (!isset($this->_uri)) {
            \Analog\Analog::log("Request URI must be set.", \Analog\Analog::ERROR);
            throw new \Sherlock\common\exceptions\RuntimeException("Request URI must be set.");
        }

        \Analog\Analog::log("Request->_uri: ".$this->_uri, \Analog\Analog::DEBUG);
        \Analog\Analog::log("Request->_data: ".$this->_data, \Analog\Analog::DEBUG);
        \Analog\Analog::log("Request->_action: ".$this->_action, \Analog\Analog::DEBUG);
        $client = new Client();

        $action = $this->_action;
        try {
            $response = $client->$action($this->_uri, null, $this->_data)->send();

        } catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
            Analog::log("Request->execute() - ClientErrorResponseException - Request failed from ".$class, Analog::ERROR);
            Analog::log(print_r($e->getMessage(), true), Analog::ERROR);
            Analog::log(print_r($e->getResponse()->getBody(true), true), Analog::ERROR);

            throw new \Sherlock\common\exceptions\ClientErrorResponseException($e->getResponse()->getBody(true), $e->getCode(), $e);
        } catch (\Guzzle\Http\Exception\ServerErrorResponseException $e) {
            Analog::log("Request->execute() - ServerErrorResponseException - Request failed from ".$class, Analog::ERROR);
            Analog::log(print_r($e->getMessage(), true), Analog::ERROR);
            Analog::log(print_r($e->getResponse()->getBody(true), true), Analog::ERROR);

            throw new \Sherlock\common\exceptions\ClientErrorResponseException($e->getResponse()->getBody(true), $e->getCode(), $e);
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            Analog::log("Request->execute() - BadResponseException - Request failed from ".$class, Analog::ERROR);
            Analog::log(print_r($e->getMessage(), true), Analog::ERROR);
            Analog::log(print_r($e->getResponse()->getBody(true), true), Analog::ERROR);

            throw new \Sherlock\common\exceptions\BadResponseException($e->getResponse()->getBody(true), $e->getCode(), $e);
        } catch (\Exception $e) {
            Analog::log("Request->execute() - Exception - Request failed from ".$class, Analog::ERROR);
            Analog::log(print_r($e, true), Analog::ERROR);

            throw new \Sherlock\common\exceptions\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        //This is kinda gross...
        if ($class == 'SearchRequest')
            $ret =  new \Sherlock\responses\QueryResponse($response);
        elseif ($class == 'IndexRequest')
            $ret =  new \Sherlock\responses\IndexResponse($response);
        elseif ($class == 'IndexDocumentRequest')
            $ret =  new \Sherlock\responses\IndexResponse($response);

        return $ret;
    }
}
