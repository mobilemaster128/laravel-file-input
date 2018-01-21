laravel-file-input
================

Laravel bootstrap file input support.

Handeling chunked uploads.

## Installation

Install using composer 

```sh
composer require mobilemaster/laravel-file-input
```

Add the provider to `config/app.php`

```php
'providers' => [
]
```

If you want to use te build in builder insert the facade

```php
'aliases' => array(
),
```

To publish the assets:

```sh
php artisan vendor:publish --provider="MobileMaster\LaravelFileInput\ServiceProvider" --tag=assets --force
```