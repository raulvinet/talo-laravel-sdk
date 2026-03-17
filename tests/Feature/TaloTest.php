<?php

use Illuminate\Support\Facades\Http;
use TuVendor\TaloLaravel\DTOs\CreatePaymentData;
use TuVendor\TaloLaravel\Support\Talo;

it('obtains access token', function () {
    Http::fake([
        'https://sandbox-api.talo.com.ar/users/user_123/tokens' => Http::response([
            'error' => false,
            'message' => 'ok',
            'data' => [
                'token' => 'token_test',
            ],
        ], 200),
    ]);

    $token = app(Talo::class)->getAccessToken();

    expect($token)->toBe('token_test');
});

it('creates payment', function () {
    Http::fake([
        'https://sandbox-api.talo.com.ar/users/user_123/tokens' => Http::response([
            'error' => false,
            'message' => 'ok',
            'data' => [
                'token' => 'token_test',
            ],
        ], 200),

        'https://sandbox-api.talo.com.ar/payments/' => Http::response([
            'error' => false,
            'message' => 'created',
            'data' => [
                'id' => 'pay_1',
                'payment_url' => 'https://checkout.example.com/pay_1',
            ],
        ], 200),
    ]);

    $response = app(Talo::class)->createPayment(
        new CreatePaymentData(
            external_id: 'ORDER_1',
            amount: 1000,
            redirect_url: 'https://miapp.com/ok',
            webhook_url: 'https://miapp.com/webhook'
        )
    );

    expect($response->ok)->toBeTrue()
        ->and(data_get($response->data, 'id'))->toBe('pay_1');
});
