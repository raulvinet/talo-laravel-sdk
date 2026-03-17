# Talo Laravel SDK

SDK Laravel para integrar Talo.

## Instalación

```bash
composer require tuvendor/talo-laravel-sdk
```

## Publicar config

```bash
php artisan vendor:publish --tag=talo-config
```

## Variables de entorno

```env
TALO_BASE_URL=https://sandbox-api.talo.com.ar
TALO_USER_ID=
TALO_CLIENT_ID=
TALO_CLIENT_SECRET=
TALO_TOKEN_TTL_SECONDS=3300
TALO_TIMEOUT=30
TALO_CONNECT_TIMEOUT=10
TALO_WEBHOOK_ENABLED=true
TALO_WEBHOOK_ROUTE=/webhooks/talo
TALO_WEBHOOK_SECRET=
```

## Uso

### Crear pago

```php
use TuVendor\TaloLaravel\DTOs\CreatePaymentData;
use TuVendor\TaloLaravel\Facades\TaloSdk;

$response = TaloSdk::createPayment(
    new CreatePaymentData(
        external_id: 'ORDER_1001',
        amount: 15000,
        redirect_url: route('checkout.ok'),
        webhook_url: route('talo.webhook'),
        motive: 'Compra web'
    )
);

$paymentUrl = data_get($response->data, 'payment_url');
```

### Consultar pago

```php
$response = TaloSdk::getPayment('payment_id');
```

### Actualizar precio

```php
use TuVendor\TaloLaravel\DTOs\UpdatePaymentPriceData;

$response = TaloSdk::updatePaymentPrice(
    'payment_id',
    new UpdatePaymentPriceData(amount: 20000)
);
```

### Crear customer

```php
use TuVendor\TaloLaravel\DTOs\CreateCustomerData;

$response = TaloSdk::createCustomer(
    new CreateCustomerData(
        alias: 'cliente001',
        customer_id: 'USER_1',
        name: 'Raul Vinet',
        email: 'raul@example.com',
        webhook_url: route('talo.webhook')
    )
);
```

## Recomendación de integración

1. Crear orden local.
2. Crear payment en Talo.
3. Guardar `external_id` y `payment_id`.
4. Redirigir a `payment_url`.
5. Recibir webhook.
6. Confirmar el pago consultando la API.
7. Marcar la orden como paga.

## Desarrollo local con path repository

En tu proyecto Laravel principal:

```json
"repositories": [
  {
    "type": "path",
    "url": "../talo-laravel-sdk"
  }
]
```

Luego:

```bash
composer require tuvendor/talo-laravel-sdk:@dev
```
