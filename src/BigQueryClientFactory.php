<?php

namespace Sidis405\BigQuery;

use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Cache\Adapter\Psr16Adapter;

class BigQueryClientFactory
{
    public static function createForConfig(array $bigQueryConfig): BigQueryClient
    {
        $clientConfig = array_merge([
            'projectId' => $bigQueryConfig['project_id'],
            'keyFile' => $bigQueryConfig['application_credentials_content'],
            'keyFilePath' => $bigQueryConfig['application_credentials'],
            'authCache' => self::configureCache($bigQueryConfig['auth_cache_store']),
        ], Arr::get($bigQueryConfig, 'client_options', []));

        return new BigQueryClient($clientConfig);
    }

    protected static function configureCache($cacheStore)
    {
        $store = Cache::store($cacheStore);

        $cache = new Psr16Adapter($store);

        return $cache;
    }
}
