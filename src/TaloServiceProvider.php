<?php

namespace Virulenta\TaloLaravel;

use Illuminate\Support\ServiceProvider;
use Virulenta\TaloLaravel\Support\Talo;
use Virulenta\TaloLaravel\Support\Webhook\TaloWebhookHandler;

class TaloServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/talo.php', 'talo');

        $this->app->singleton(Talo::class, function () {
            return new Talo();
        });

        $this->app->singleton(TaloWebhookHandler::class, function ($app) {
            return new TaloWebhookHandler($app->make(Talo::class));
        });

        $this->app->alias(Talo::class, 'talo-sdk');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/talo.php' => config_path('talo.php'),
        ], 'talo-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
