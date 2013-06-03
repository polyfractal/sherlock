<?php
/**
 * User: zach
 * Date: 6/3/13
 * Time: 1:00 PM
 */

namespace Sherlock\Facades\Search;

use Sherlock\common\Transport;
use Sherlock\components\QueryInterface;

class SearchWhereFacade extends SearchRequest
{
    public function __construct(Transport $transport, QueryInterface $query)
    {

    }

    public function andWhere(QueryInterface $query)
    {

    }

    public function orWhere(QueryInterface $query)
    {

    }
}