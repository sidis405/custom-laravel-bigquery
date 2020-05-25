<?php

namespace Sidis405\BigQuery;

use Illuminate\Support\ServiceProvider;
use Google\Cloud\BigQuery\BigQueryClient;
use Sidis405\BigQuery\Exceptions\InvalidConfiguration;

class BigQueryServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/bigquery.php' => config_path('bigquery.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/bigquery.php', 'bigquery');

        $bigQueryConfig = config('bigquery');

        $this->app->bind(BigQueryClient::class, function () use ($bigQueryConfig) {
            $this->guardAgainstInvalidConfiguration($bigQueryConfig);

            return BigQueryClientFactory::createForConfig($bigQueryConfig);
        });

        $this->app->alias(BigQueryClient::class, 'bigquery');
    }

    protected function guardAgainstInvalidConfiguration(array $bigQueryConfig = null)
    {
        if (! file_exists($bigQueryConfig['application_credentials']) && !count($bigQueryConfig['application_credentials_content'])) {
            throw InvalidConfiguration::credentialsJsonDoesNotExist($bigQueryConfig['application_credentials']);
        }
    }
}
