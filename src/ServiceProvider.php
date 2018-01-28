<?php

namespace MobileMaster\LaravelFileInput;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('fileinput', function ($app) {
            return $this->app->make(Manager::class, ['request' => $app['request']]);
        });
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishConfig();
        
        $this->publishAssets();
    }

    private function publishConfig()
    {
        $configPath = $this->packagePath('config/fileinput.php');

        $this->publishes([
            $configPath => config_path('fileinput.php'),
        ], 'config');

        $this->mergeConfigFrom($configPath, 'fileinput');
    }

    private function publishAssets()
    {
        $this->publishes([
            $this->packagePath('resources/assets') => public_path('vendor/mobilemaster/fileinput'),
        ], 'assets');
    }

    private function packagePath($path)
    {
        return __DIR__."/../$path";
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
