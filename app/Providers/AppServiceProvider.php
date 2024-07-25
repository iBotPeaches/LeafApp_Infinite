<?php

namespace App\Providers;

use App\Adapters\FileUtilInterface;
use App\Adapters\FileUtils;
use App\Services\DotApi\ApiClient as HaloApiClient;
use App\Services\DotApi\InfiniteInterface;
use App\Services\FaceIt\ApiClient as FaceItApiClient;
use App\Services\FaceIt\TournamentInterface;
use App\Services\Tinify\ApiClient as ImageApiClient;
use App\Services\Tinify\ImageInterface;
use App\Support\Schedule\ScheduleTimer;
use App\Support\Schedule\ScheduleTimerInterface;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        $this->app->singleton(InfiniteInterface::class, function ($app) {
            return new HaloApiClient($app['config']['services']['dotapi']);
        });

        $this->app->singleton(TournamentInterface::class, function ($app) {
            return new FaceItApiClient($app['config']['services']['faceit']);
        });

        $this->app->singleton(ImageInterface::class, function ($app) {
            return new ImageApiClient($app['config']['services']['tinify']);
        });

        $this->app->singleton(ScheduleTimerInterface::class, function ($app) {
            return new ScheduleTimer;
        });

        // @codeCoverageIgnoreStart
        $this->app->bind(FileUtilInterface::class, FileUtils::class);

        Blade::directive('th', function ($expression) {
            return "<?php echo (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format({$expression}); ?>";
        });

        RateLimiter::for('uploads', function (Request $request) {
            return Limit::perHour(10)->by($request->ip())->response(function () {
                return response()->view('pages.errors.429', [], Response::HTTP_TOO_MANY_REQUESTS);
            });
        });

        RateLimiter::for('ban', function (Request $request) {
            return Limit::perDay(5)->by($request->ip())->response(function () {
                return response()->view('pages.errors.429-ban', [], Response::HTTP_TOO_MANY_REQUESTS);
            });
        });
        // @codeCoverageIgnoreEnd
    }
}
