<?php

use Illuminate\Support\Facades\Route;
use TuVendor\TaloLaravel\Http\Controllers\TaloWebhookController;

if (config('talo.webhook_enabled', true)) {
    Route::post(config('talo.webhook_route', '/webhooks/talo'), TaloWebhookController::class)
        ->name('talo.webhook');
}
