<?php
/**
 * User: Ilia Shakitko
 * Date: 2013-05-16
 * Time: 12:00 PM
 * @package Sherlock\components\queries
 */
namespace Sherlock\components\queries;

use Sherlock\components;

/**
 * Class descibes 'text' type of query
 *
 * @method \Sherlock\components\queries\Text field() field(\string $value)
 * @method \Sherlock\components\queries\Text text() text(\string $value)
 */
class Text extends \Sherlock\components\BaseComponent implements \Sherlock\components\QueryInterface
{
    /**
     * Class constructor
     *
     * @param string $hashMap Hash map
     */
    public function __construct($hashMap = null)
    {

        parent::__construct($hashMap);
    }

    /**
     * Method makes an array for using it when composing a request
     *
     * @see \Sherlock\components\QueryInterface::toArray()
     *
     * @return array Array with 'text' query type
     */
    public function toArray()
    {
        $ret = array (
                'text' => array (
                            $this->params["field"] => $this->params["text"]
                          ),
               );

        return $ret;
    }

}
