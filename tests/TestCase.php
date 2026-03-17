<?php

namespace TuVendor\TaloLaravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TuVendor\TaloLaravel\TaloServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TaloServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('talo.base_url', 'https://sandbox-api.talo.com.ar');
        $app['config']->set('talo.user_id', 'user_123');
        $app['config']->set('talo.client_id', 'client_123');
        $app['config']->set('talo.client_secret', 'secret_123');
        $app['config']->set('talo.webhook_enabled', true);
        $app['config']->set('talo.webhook_secret', 'abc123');
    }
}
