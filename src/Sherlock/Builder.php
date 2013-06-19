<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 10:37 AM
 */

namespace Sherlock;

use Sherlock\Facades;

/**
 * Class Builder
 * @package Sherlock
 */
class Builder
{

    /**
     * @return facades\QueryFacade
     */
    public static function query()
    {
        return new facades\QueryFacade();
    }


    /**
     * @return facades\FilterFacade
     */
    public static function filter()
    {
        return new facades\FilterFacade();
    }


    /**
     * @return facades\FacetFacade
     */
    public static function facet()
    {
        return new facades\FacetFacade();
    }


    /**
     * @return facades\HighlightFacade
     */
    public static function highlighter()
    {
        return new facades\HighlightFacade();
    }


    /**
     * @return facades\IndexSettingsFacade
     */
    public static function indexSettingsBuilder()
    {
        return new facades\IndexSettingsFacade();
    }


    /**
     * @return facades\MappingFacade
     */
    public static function mapping($type)
    {
        return new facades\MappingFacade($type);
    }


    /**
     * @return facades\SortFacade
     */
    public static function sort()
    {
        return new facades\SortFacade();
    }


}