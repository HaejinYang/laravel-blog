<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\Redis;

class PrometheusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CollectorRegistry::class, function ($app) {
            return new CollectorRegistry(new Redis(['host' => 'redis', 'password' => 'asgcvxcqed123']));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
