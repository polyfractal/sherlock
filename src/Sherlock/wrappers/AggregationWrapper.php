<?php

namespace Sherlock\wrappers;

/**
 * Class AggregationWrapper
 * @package Sherlock\wrappers
 *
 *
 * @method \Sherlock\components\aggregations\Terms Terms() Terms()
 * @method \Sherlock\components\aggregations\Range Range() Range()
 * @method \Sherlock\components\aggregations\Histogram Histogram() Histogram()
 * @method \Sherlock\components\aggregations\DateHistogram DateHistogram() DateHistogram()
 * @method \Sherlock\components\aggregations\Filter Filter() Filter()
 * @method \Sherlock\components\aggregations\Filters Filters() Filters()
 * @method \Sherlock\components\aggregations\Query Query() Query()
 * @method \Sherlock\components\aggregations\Statistical Statistical() Statistical()
 * @method \Sherlock\components\aggregations\TermsStats TermsStats() TermsStats()
 * @method \Sherlock\components\aggregations\GeoDistance GeoDistance() GeoDistance()
 */
class AggregationWrapper
{
    /**
     * @var \Sherlock\components\AggregationInterface
     */
    protected $aggregation;


    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $class = '\\Sherlock\\components\\aggregations\\' . $name;

        if (count($arguments) > 0) {
            $this->aggregation = new $class($arguments[0]);
        } else {
            $this->aggregation = new $class();
        }

        return $this->aggregation;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->aggregation;
    }

}
