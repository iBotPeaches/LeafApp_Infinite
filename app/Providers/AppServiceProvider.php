<?php

namespace App\Providers;

use App\Services\Autocode\ApiClient as HaloApiClient;
use App\Services\Autocode\InfiniteInterface;
use App\Services\XboxApi\ApiClient as XboxApiClient;
use App\Services\XboxApi\XboxInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->singleton(InfiniteInterface::class, function ($app) {
            return new HaloApiClient($app['config']['services']['autocode']);
        });

        $this->app->singleton(XboxInterface::class, function ($app) {
            return new XboxApiClient($app['config']['services']['xboxapi']);
        });
    }
}
