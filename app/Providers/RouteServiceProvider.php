<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The namespace for the controllers.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        parent::boot();

        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapApiRoutes();
            // $this->mapAmpRoutes();
            $this->mapNewApiRoutes();
			$this->mapWebRoutes();
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     * Define the "web" routes for the application.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "amp" routes for the application.
     */
    // protected function mapAmpRoutes(): void
    // {
    //     Route::prefix('amp')
    //         ->middleware('web')
    //         ->namespace($this->namespace)
    //         ->group(base_path('routes/web.php'));
    // }

    /**
     * Define the new API routes for the application.
     */
    protected function mapNewApiRoutes(): void
    {
        Route::prefix('api23mar2023')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api23mar2023.php'));
    }
}

