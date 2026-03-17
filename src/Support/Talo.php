<?php

namespace TuVendor\TaloLaravel\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use TuVendor\TaloLaravel\DTOs\CreateCustomerData;
use TuVendor\TaloLaravel\DTOs\CreatePaymentData;
use TuVendor\TaloLaravel\DTOs\UpdatePaymentPriceData;
use TuVendor\TaloLaravel\Exceptions\TaloAuthenticationException;
use TuVendor\TaloLaravel\Exceptions\TaloRequestException;

class Talo
{
    protected function baseUrl(): string
    {
        return rtrim((string) config('talo.base_url'), '/');
    }

    protected function userId(): string
    {
        return (string) config('talo.user_id');
    }

    protected function clientId(): string
    {
        return (string) config('talo.client_id');
    }

    protected function clientSecret(): string
    {
        return (string) config('talo.client_secret');
    }

    protected function tokenCacheKey(): string
    {
        return (string) config('talo.token_cache_key', 'talo.access_token');
    }

    protected function tokenTtlSeconds(): int
    {
        return (int) config('talo.token_ttl_seconds', 3300);
    }

    protected function timeout(): int
    {
        return (int) config('talo.timeout', 30);
    }

    protected function connectTimeout(): int
    {
        return (int) config('talo.connect_timeout', 10);
    }

    protected function http(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl())
            ->timeout($this->timeout())
            ->connectTimeout($this->connectTimeout())
            ->acceptJson()
            ->asJson();
    }

    protected function authenticatedHttp(): PendingRequest
    {
        return $this->http()->withToken($this->getAccessToken());
    }

    public function getAccessToken(bool $forceRefresh = false): string
    {
        $cacheKey = $this->tokenCacheKey();

        if (!$forceRefresh) {
            $cached = Cache::get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                return $cached;
            }
        }

        $response = $this->http()->post("/users/{$this->userId()}/tokens", [
            'client_id' => $this->clientId(),
            'client_secret' => $this->clientSecret(),
        ]);

        $json = $response->json();

        if (!$response->successful() || !isset($json['data']['token'])) {
            throw new TaloAuthenticationException(
                'No se pudo obtener el token de Talo.',
                [
                    'status' => $response->status(),
                    'response' => $json,
                ]
            );
        }

        $token = (string) $json['data']['token'];

        Cache::put($cacheKey, $token, now()->addSeconds($this->tokenTtlSeconds()));

        return $token;
    }

    protected function send(callable $callback): TaloResponse
    {
        $response = $callback($this->authenticatedHttp());

        if ($response->status() === 401) {
            Cache::forget($this->tokenCacheKey());

            $response = $callback(
                $this->http()->withToken($this->getAccessToken(true))
            );
        }

        return $this->handleResponse($response);
    }

    protected function handleResponse(Response $response): TaloResponse
    {
        $json = $response->json();

        if (!is_array($json)) {
            throw new TaloRequestException(
                'Respuesta inválida de Talo.',
                [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]
            );
        }

        $wrapped = TaloResponse::fromHttp($json, $response->status());

        if (!$wrapped->ok) {
            throw new TaloRequestException(
                $wrapped->message ?: 'Error en request a Talo.',
                [
                    'status' => $wrapped->status,
                    'response' => $wrapped->raw,
                ]
            );
        }

        return $wrapped;
    }

    public function createPayment(CreatePaymentData $data): TaloResponse
    {
        return $this->send(
            fn (PendingRequest $http) => $http->post('/payments/', $data->toArray($this->userId()))
        );
    }

    public function getPayment(string $paymentId): TaloResponse
    {
        return $this->send(
            fn (PendingRequest $http) => $http->get("/payments/{$paymentId}")
        );
    }

    public function updatePaymentPrice(string $paymentId, UpdatePaymentPriceData $data): TaloResponse
    {
        return $this->send(
            fn (PendingRequest $http) => $http->put("/payments/{$paymentId}/price", $data->toArray())
        );
    }

    public function createCustomer(CreateCustomerData $data): TaloResponse
    {
        return $this->send(
            fn (PendingRequest $http) => $http->post('/customers/', $data->toArray($this->userId()))
        );
    }

    public function getCustomerTransaction(string $customerId, string $transactionId): TaloResponse
    {
        return $this->send(
            fn (PendingRequest $http) => $http->get("/customers/{$customerId}/transactions/{$transactionId}")
        );
    }

    public function flushTokenCache(): void
    {
        Cache::forget($this->tokenCacheKey());
    }
}
