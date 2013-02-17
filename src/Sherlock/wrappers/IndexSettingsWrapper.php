<?php
/**
 * User: Zachary Tong
 * Date: 2/10/13
 * Time: 1:05 PM
 */
namespace sherlock\wrappers;

use sherlock\components\queries;
use sherlock\common\exceptions;



/**
 * @todo add index.routing.allocation.include/exclude
 *
 * @method \sherlock\wrappers\IndexSettingsWrapper number_of_replicas() number_of_replicas(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper auto_expand_replicas() auto_expand_replicas(\string $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper refresh_interval() refresh_interval(\string $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper term_index_interval() term_index_interval(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper term_index_divisor() term_index_divisor(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper gc_deletes() gc_deletes()
 *
 * @method \sherlock\wrappers\IndexSettingsWrapper blocks__read_only() blocks__read_only(bool $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper blocks__read() blocks__read(bool $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper blocks__write() blocks__write(bool $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper blocks__metadata() blocks__metadata(bool $value)
 *
 * @method \sherlock\wrappers\IndexSettingsWrapper translog__flush_threshold_ops() translog__flush_threshold_ops(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper translog__flush_threshold_size() translog__flush_threshold_size(\string $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper translog__flush_threshold_period() translog__flush_threshold_period(\string $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper translog__disable_flush() translog__disable_flush(\int $value)
 *
 * @method \sherlock\wrappers\IndexSettingsWrapper cache__filter__max_size() cache__filter__max_size(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper cache__filter__expire() cache__filter__expire(\string $value)
 *
 * @method \sherlock\wrappers\IndexSettingsWrapper gateway__snapshot_interval() gateway__snapshot_interval(\string $value)
 *
 * @method \sherlock\wrappers\IndexSettingsWrapper recovery__initial_shards() recovery__initial_shards(\int $value)
 *
 * @method \sherlock\wrappers\IndexSettingsWrapper ttl__disable_purge() ttl__disable_purge(bool $value)
 *
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__expunge_deletes_allowed() merge__policy__expunge_deletes_allowed(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__floor_segment() merge__policy__floor_segment(\string $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__max_merge_at_once() merge__policy__max_merge_at_once(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__max_merge_at_once_explicit() merge__policy__max_merge_at_once_explicit(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__max_merged_segment() merge__policy__max_merged_segment(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__segments_per_tier() merge__policy__segments_per_tier(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper reclaim_deletes_weight() reclaim_deletes_weight(float $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper compound_format() compound_format(bool $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__scheduler__max_thread_count() merge__scheduler__max_thread_count(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__merge_factor() merge__policy__merge_factor(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__min_merge_size() merge__policy__min_merge_size(\string $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__max_merge_size() merge__policy__max_merge_size(\string $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__maxMergeDocs() merge__policy__maxMergeDocs(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__min_merge_docs() merge__policy__min_merge_docs(\int $value)
 * @method \sherlock\wrappers\IndexSettingsWrapper merge__policy__max_merge_docs() merge__policy__max_merge_docs(\int $value)
 */
class IndexSettingsWrapper
{
	/**
	 * @var array
	 */
	protected $params;

	public function __call($name, $arguments)
	{
		$name = str_replace("__", ".", $name);
		$this->params[$name] = $arguments[0];
		return $this;
	}

	public function toArray()
	{
		return $this->params;
	}




}