<?php
/**
 * User: Zachary Tong
 * Date: 2/10/13
 * Time: 1:05 PM
 */
namespace sherlock\Request;

use sherlock\components\queries;
use sherlock\common\exceptions;


/**
 * @method \sherlock\components\queries\Bool Bool() Bool()
 * @method \sherlock\components\queries\Boosting Boosting() Boosting()
 * @method \sherlock\components\queries\ConstantScore ConstantScore() ConstantScore()
 * @method \sherlock\components\queries\CustomBoostFactor CustomBoostFactor() CustomBoostFactor()
 * @method \sherlock\components\queries\CustomFiltersScore CustomFiltersScore() CustomFiltersScore()
 * @method \sherlock\components\queries\CustomScore CustomScore() CustomScore()
 * @method \sherlock\components\queries\DisMax DisMax() DisMax()
 * @method \sherlock\components\queries\Field Field() Field()
 * @method \sherlock\components\queries\FilteredQuery FilteredQuery() FilteredQuery()
 * @method \sherlock\components\queries\Fuzzy Fuzzy() Fuzzy()
 * @method \sherlock\components\queries\FuzzyLikeThis FuzzyLikeThis() FuzzyLikeThis()
 * @method \sherlock\components\queries\FuzzyLikeThisField FuzzyLikeThisField() FuzzyLikeThisField()
 * @method \sherlock\components\queries\HasChild HasChild() HasChild()
 * @method \sherlock\components\queries\HasParent HasParent() HasParent()
 * @method \sherlock\components\queries\Ids Ids() Ids()
 * @method \sherlock\components\queries\Indices Indices() Indices()
 * @method \sherlock\components\queries\Match Match() Match()
 * @method \sherlock\components\queries\MatchAll MatchAll() MatchAll()
 * @method \sherlock\components\queries\MoreLikeThis MoreLikeThis() MoreLikeThis()
 * @method \sherlock\components\queries\MoreLikeThisField MoreLikeThisField() MoreLikeThisField()
 * @method \sherlock\components\queries\Nested Nested() Nested()
 * @method \sherlock\components\queries\Prefix Prefix() Prefix()
 * @method \sherlock\components\queries\QueryString QueryString() QueryString()
 * @method \sherlock\components\queries\QueryStringMultiField QueryStringMultiField() QueryStringMultiField()
 * @method \sherlock\components\queries\Range Range() Range()
 * @method \sherlock\components\queries\Term Term() Term()
 * @method \sherlock\components\queries\Terms Terms() Terms()
 * @method \sherlock\components\queries\TopChildren TopChildren() TopChildren()
 * @method \sherlock\components\queries\Wildcard Wildcard() Wildcard()
 */
class QueryWrapper
{
	protected $query;

	public function __call($name, $arguments)
	{
		$this->query =  new $name();
		return $this->query;
	}

	public function __toString()
	{
		return (string)$this->query;
	}

}