<?php
/**
 * User: Zachary Tong
 * Date: 2013-02-16
 * Time: 09:24 PM
 * Auto-generated by "generate.php"
 * @package Sherlock\components\queries
 */
namespace Sherlock\components\queries;

use Sherlock\components;

/**
 * @method \Sherlock\components\queries\Prefix field() field(\string $value)
 * @method \Sherlock\components\queries\Prefix value() value(\string $value)
 * @method \Sherlock\components\queries\Prefix boost() boost(\float $value) Default: 2.0
 * @method \Sherlock\components\queries\Prefix analyzer() analyzer(\string $value) Default: "default"
 * @method \Sherlock\components\queries\Prefix slop() slop(\int $value) Default: 3
 * @method \Sherlock\components\queries\Prefix max_expansions() max_expansions(\int $value) Default: 100

 */
class Prefix extends \Sherlock\components\BaseComponent implements \Sherlock\components\QueryInterface
{
    public function __construct($hashMap = null)
    {
        $this->params['field'] = null;
        $this->params['value'] = null;
        $this->params['boost'] = 2.0;
        $this->params['analyzer'] = "default";
        $this->params['slop'] = 3;
        $this->params['max_expansions'] = 100;

        parent::__construct($hashMap);
    }

    public function toArray()
    {
        $ret = array (
  'prefix' =>
  array (
    $this->params["field"] =>
    array (
      'value' => $this->params["value"],
      'boost' => $this->params["boost"],
      'analyzer' => $this->params["analyzer"],
      'slop' => $this->params["slop"],
      'max_expansions' => $this->params["max_expansions"],
    ),
  ),
);

        return $ret;
    }

}
