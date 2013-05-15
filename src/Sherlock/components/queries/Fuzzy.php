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
 * @method \Sherlock\components\queries\Fuzzy field() field(\string $value)
 * @method \Sherlock\components\queries\Fuzzy value() value(\string $value)
 * @method \Sherlock\components\queries\Fuzzy boost() boost(\float $value) Default: 1.0
 * @method \Sherlock\components\queries\Fuzzy min_similarity() min_similarity(\float $value) Default: 0.2
 * @method \Sherlock\components\queries\Fuzzy prefix_length() prefix_length(\int $value) Default: 0
 * @method \Sherlock\components\queries\Fuzzy max_expansions() max_expansions(\int $value) Default: 10

 */
class Fuzzy extends \Sherlock\components\BaseComponent implements \Sherlock\components\QueryInterface
{
	protected $_allowed;

    public function __construct($hashMap = null)
    {
        $this->params['field'] = null;	// Setting as default for argumentExists check
        $this->params['value'] = null;	// idem
        $this->params['boost'] = 1.0;
        $this->params['min_similarity'] = 0.2;
        $this->params['prefix_length'] = 0;
        $this->params['max_expansions'] = 10;
        parent::__construct($hashMap);
    }

    public function toArray()
    {
        $ret = array (
		'fuzzy' =>
		array (
			$this->params["field"] =>
				array (
					  'value' => $this->params["value"],
					  'boost' => $this->params["boost"],
					  'min_similarity' => $this->params["min_similarity"],
					  'prefix_length' => $this->params["prefix_length"],
					  'max_expansions' => $this->params["max_expansions"],
    			),
			),
		);
    	return $ret;
    }

}
