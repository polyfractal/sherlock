<?php
/**
 * Created by IntelliJ IDEA.
 * User: umutcan
 * Date: 25.11.2014
 * Time: 14:34
 */

namespace Sherlock\components\aggregations;

use sherlock\components\BaseComponent;
use Sherlock\components\AggregationInterface;

abstract class BaseAggs extends BaseComponent implements AggregationInterface {

    public function __construct($hashMap = null){
        $this->params['aggs']  = null;
        parent::__construct($hashMap);
    }

    public function aggs(AggregationInterface $aggs){
        $this->params['aggs'] = $aggs;
        return $this;
    }

} 