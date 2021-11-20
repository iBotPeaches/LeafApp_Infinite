<?php

namespace App\Providers;

use App\Services\HaloDotApi\ApiClient;
use App\Services\HaloDotApi\InfiniteInterface;
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
            return new ApiClient($app['config']['services']['halodotapi']);
        });
    }
}
