<?php

namespace Sidis405\BigQuery;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sidis405\BigQuery\BigQuery
 */
class BigQueryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bigquery';
    }
}
