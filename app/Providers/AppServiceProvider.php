<?php

namespace App\Providers;

use App\Services\Autocode\ApiClient as HaloApiClient;
use App\Services\Autocode\InfiniteInterface;
use App\Services\FaceIt\TournamentInterface;
use App\Services\FaceIt\ApiClient as FaceItApiClient;
use App\Services\XboxApi\ApiClient as XboxApiClient;
use App\Services\XboxApi\XboxInterface;
use Illuminate\Support\Facades\Blade;
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

        $this->app->singleton(TournamentInterface::class, function ($app) {
            return new FaceItApiClient($app['config']['services']['faceit']);
        });

        // @codeCoverageIgnoreStart
        Blade::directive('th', function ($expression) {
            return "<?php echo (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format({$expression}); ?>";
        });
        // @codeCoverageIgnoreEnd
    }
}
