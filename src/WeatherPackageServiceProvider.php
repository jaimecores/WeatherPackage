<?php

namespace JaimeCores\WeatherPackage;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use JaimeCores\WeatherPackage\Console\InstallWeatherPackage;
use JaimeCores\WeatherPackage\Console\GetWeatherPackage;
use JaimeCores\WeatherPackage\Http\Middleware\SetLanguage;

class WeatherPackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'weatherpackage');
    }

    public function boot()
    {
        // Register routes
        $this->registerRoutes();

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'weatherpackage');

        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'weatherpackage');

        // Register a global middleware
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(SetLanguage::class);

        // Register a route specific middleware
        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('web', SetLanguage::class);

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('weatherpackage.php'),
            ], 'config');

            $this->commands([
                InstallWeatherPackage::class,
                GetWeatherPackage::class,
            ]);

            if (! class_exists('CreateGuestsTable') and ! class_exists('CreateForecastsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_guests_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_guests_table.php'),
                    __DIR__ . '/../database/migrations/create_forecasts_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_forecasts_table.php'),
                ], 'migrations');
            }

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/weatherpackage'),
            ], 'views');

            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('weatherpackage'),
            ], 'assets');
        }
    }

    // Register the routes using a group with the specific configuration
    public function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'../../routes/web.php');
        });
    }

    // Configure the routes with prefix and middleware
    public function routeConfiguration()
    {
        return [
            'prefix' => config('weatherpackage.prefix'),
            'middleware' => config('weatherpackage.middleware'),
        ];
    }
}