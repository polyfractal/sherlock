<?php
/**
 * User: Zachary Tong
 * Date: 2/10/13
 * Time: 1:05 PM
 */
namespace Sherlock\wrappers;

use Sherlock\components\queries;

/**
 * @method \Sherlock\components\queries\Bool Bool() Bool()
 * @method \Sherlock\components\queries\Boosting Boosting() Boosting()
 * @method \Sherlock\components\queries\ConstantScore ConstantScore() ConstantScore()
 * @method \Sherlock\components\queries\CustomBoostFactor CustomBoostFactor() CustomBoostFactor()
 * @method \Sherlock\components\queries\CustomFiltersScore CustomFiltersScore() CustomFiltersScore()
 * @method \Sherlock\components\queries\CustomScore CustomScore() CustomScore()
 * @method \Sherlock\components\queries\DisMax DisMax() DisMax()
 * @method \Sherlock\components\queries\Field Field() Field()
 * @method \Sherlock\components\queries\FilteredQuery FilteredQuery() FilteredQuery()
 * @method \Sherlock\components\queries\Fuzzy Fuzzy() Fuzzy()
 * @method \Sherlock\components\queries\FuzzyLikeThis FuzzyLikeThis() FuzzyLikeThis()
 * @method \Sherlock\components\queries\FuzzyLikeThisField FuzzyLikeThisField() FuzzyLikeThisField()
 * @method \Sherlock\components\queries\HasChild HasChild() HasChild()
 * @method \Sherlock\components\queries\HasParent HasParent() HasParent()
 * @method \Sherlock\components\queries\Ids Ids() Ids()
 * @method \Sherlock\components\queries\Indices Indices() Indices()
 * @method \Sherlock\components\queries\Match Match() Match()
 * @method \Sherlock\components\queries\MatchAll MatchAll() MatchAll()
 * @method \Sherlock\components\queries\MoreLikeThis MoreLikeThis() MoreLikeThis()
 * @method \Sherlock\components\queries\MoreLikeThisField MoreLikeThisField() MoreLikeThisField()
 * @method \Sherlock\components\queries\Nested Nested() Nested()
 * @method \Sherlock\components\queries\Prefix Prefix() Prefix()
 * @method \Sherlock\components\queries\QueryString QueryString() QueryString()
 * @method \Sherlock\components\queries\QueryStringMultiField QueryStringMultiField() QueryStringMultiField()
 * @method \Sherlock\components\queries\Range Range() Range()
 * @method \Sherlock\components\queries\Term Term() Term()
 * @method \Sherlock\components\queries\Terms Terms() Terms()
 * @method \Sherlock\components\queries\TopChildren TopChildren() TopChildren()
 * @method \Sherlock\components\queries\Wildcard Wildcard() Wildcard()
 * @method \Sherlock\components\queries\Raw Raw() Raw()
 */
class QueryWrapper
{
    /**
     * @var \Sherlock\components\QueryInterface
     */
    protected $query;

    public function __call($name, $arguments)
    {
        $class = '\\Sherlock\\components\\queries\\'.$name;

        if (count($arguments) > 0)
            $this->query =  new $class($arguments[0]);
        else
            $this->query =  new $class();

        return $this->query;
    }

    public function __toString()
    {
        return (string) $this->query;
    }

}
