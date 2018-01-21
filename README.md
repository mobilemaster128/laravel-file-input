laravel-file-input
================

Laravel bootstrap file input support.

Handeling chunked uploads.

## 1. Installation

1. Require the package using composer:

    ```
    composer require mobilemaster/laravel-file-input
    ```

2. Add the service provider to the `providers` in `config/app.php`:

    > Laravel 5.5 uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider

    ```php
    MobileMaster\LaravelFileInput\ServiceProvider::class,
    ```

3. Publish the public assets:

    ```
    php artisan vendor:publish --provider="MobileMaster\LaravelFileInput\ServiceProvider" --tag=assets
    ```

## 2. Updating

1. To update this package, first update the composer package:

    ```
    composer update mobilemaster/laravel-file-input
    ```

2. Then, publish the public assets with the `--force` flag to overwrite existing files

    ```
    php artisan vendor:publish --provider="MobileMaster\LaravelFileInput\ServiceProvider" --tag=assets --force
    ```
