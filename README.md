# BOG Payment Gateway

The BOG Payment package provides seamless integration with the Bank of Georgia's payment gateway, enabling Laravel applications to process payments efficiently.


[![Latest Version on Packagist](https://img.shields.io/packagist/v/Jshar/bog-payment.svg?style=flat-square)](https://packagist.org/packages/Jshar/bog-payment)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Jshar/bog-payment/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/Jshar/bog-payment/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/Jshar/bog-payment/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/Jshar/bog-payment/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/Jshar/bog-payment.svg?style=flat-square)](https://packagist.org/packages/Jshar/bog-payment)

### Features
- Payment Processing: Initiate and manage transactions through the Bank of Georgia.
- Transaction Status: Retrieve and handle the status of payments.
- Secure Communication: Ensure secure data transmission with the payment gateway.

## Installation

You can install the package via composer:

```bash
composer require Jshar/bog-payment
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="bog-payment-config"
```

This is the contents of the published config file:

```php
<?php

/*
|--------------------------------------------------------------------------
| BOG Payment Configuration
|--------------------------------------------------------------------------
|
| This file is for setting up the Bank of Georgia payment gateway integration.
| You can define your callback URLs, API credentials, and other necessary
| settings here. Make sure to update these values in your environment file.
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by BOG to send payment notifications to your application.
    | Make sure this endpoint is accessible publicly and handles the callback
    | appropriately to update your payment records.
    |
    */
    'callback_url' => env('BOG_CALLBACK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Redirect URLs
    |--------------------------------------------------------------------------
    |
    | After the payment process, users will be redirected to these URLs depending
    | on whether the payment was successful or failed. Set these URLs to ensure
    | a smooth user experience.
    |
    */
    'redirect_urls' => [
        /*
        | URL to redirect to on successful payment
        */
        'success' => env('BOG_REDIRECT_SUCCESS'),

        /*
        | URL to redirect to on failed payment
        */
        'fail' => env('BOG_REDIRECT_FAIL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | BOG API Credentials
    |--------------------------------------------------------------------------
    |
    | These credentials are used to authenticate your application with the
    | Bank of Georgia payment API. Make sure to keep these values secure.
    |
    */
    'client_id' => env('BOG_CLIENT_ID', ''),
    'secret' => env('BOG_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | BOG Payment API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for accessing the Bank of Georgia payment API. You can set
    | this to the test or live endpoint depending on your environment.
    |
    */
    'base_url' => env('BOG_BASE_URL', 'https://api.bog.ge/payments/v1'),
    
    /*
    |--------------------------------------------------------------------------
    | BOG Public Key
    |--------------------------------------------------------------------------
    |
    | This public key is used to verify the signature of the callback requests
    | sent by the Bank of Georgia payment gateway. Make sure to keep this key
    | up to date in your environment file.
    | Here you can see the latest public key: https://api.bog.ge/docs/payments/standard-process/callback
    |
    */
    'public_key' => env('BOG_PUBLIC_KEY')
];

```

## Usage

```php
use Jshar\BogPayment\Facades\Pay;
// ...
$paymentDetails = Pay::orderId($transaction->id)
            ->redirectUrl(route('bog.v1.transaction.status', ['transaction_id' => $transaction->id]))
            ->amount($data['total_amount'])
            ->process();
```

## Building the payload

Although the package provides a convenient way to initiate payments, you can also build the payment payload manually using the provided traits.

The BuildsPayment trait helps you build the payload for payments quickly by providing the following methods

Here's how you do it:


```php

getPayload(): // Retrieves the current payload array.

orderId($externalOrderId): // Sets the external order ID for the payment.

callbackUrl($callbackUrl): // Sets a custom callback URL for the payment process.

redirectUrl($statusUrl): // Sets both success and fail URLs to the same value for redirection after the payment.

redirectUrls($failUrl, $successUrl): // Sets separate fail and success URLs for redirection after the payment.

amount($totalAmount, $currency = 'GEL', $basket = []): // Defines the total amount, currency, and optionally, the basket details for the payment.

// These methods allow for easy customization of the payment payload to suit various payment requirements.
```

## Callback Handling

The package handles callback behavior automatically. When a payment is processed, it will send a POST request to your callback URL with the payment details. The package then verifies the request's signature to ensure its authenticity and fires the NikaJshar\BogPayment\Events\TransactionStatusUpdated event, which contains all relevant payment details.

To utilize this functionality, register an event listener in your application to capture and respond to the transaction status updates as needed.

Example: Registering a Listener for Transaction Status Updates
Add the following code to your event listener:

```php
namespace App\Listeners;

use Jshar\BogPayment\Events\TransactionStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleTransactionStatusUpdate implements ShouldQueue
{
use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \Jshar\BogPayment\Events\TransactionStatusUpdated  $event
     * @return void
     */
    public function handle(array $event)
    {
        // Implement your logic here
    }
```
### Setting Up the Event Listener

Register the event listener in your EventServiceProvider to ensure it's triggered when the TransactionStatusUpdated event is fired:

```php
protected $listen = [
    \Jshar\BogPayment\Events\TransactionStatusUpdated::class => [
        \App\Listeners\HandleTransactionStatusUpdate::class,
    ],
];
```
This setup allows your application to handle transaction status updates efficiently, enabling you to respond to each status change in real time.


## Handling Transaction Status

The package provides a convenient way to retrieve the status of a transaction using the `Transaction` Facade's `get()` method. This method sends a GET request to the Bank of Georgia payment API to retrieve the transaction status.

Here's how you can use it:

```php
use Jshar\BogPayment\Facades\Transaction;

$transactionDetails = Transaction::get($order_id); // Returns array of transaction details
```

See example of the response [Official Documentation](https://api.bog.ge/docs/payments/standard-process/get-payment-details)


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
