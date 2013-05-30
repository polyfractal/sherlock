<?php
/**
 * User: zach
 * Date: 5/29/13
 * Time: 3:55 PM
 */

namespace Sherlock\components\queries;


use Sherlock\components\BaseComponent;

/**
 * Class BaseFuzzyLikeThis
 * @package Sherlock\components\queries
 */
abstract class BaseFuzzyLikeThis extends BaseComponent
{
    /**
     * @param string $value
     *
     * @return $this
     */
    public function like_text($value)
    {
        $this->params['like_text'] = $value;
        return $this;
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function max_query_terms($value)
    {
        $this->params['max_query_terms'] = $value;
        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function analyzer($value)
    {
        $this->params['analyzer'] = $value;
        return $this;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function ignore_tf($value)
    {
        $this->params['ignore_tf'] = $value;
        return $this;
    }

    /**
     * @param float $value
     *
     * @return $this
     */
    public function boost($value)
    {
        $this->params['boost'] = $value;
        return $this;
    }

    /**
     * @param float $value
     *
     * @return $this
     */
    public function min_similarity($value)
    {
        $this->params['min_similarity'] = $value;
        return $this;
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function prefix_length($value)
    {
        $this->params['prefix_length'] = $value;
        return $this;
    }
}