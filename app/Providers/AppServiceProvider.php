<?php

namespace App\Providers;

use GuzzleHttp\Middleware;
use Illuminate\Log\LogManager;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use LastFmApi\Api\ArtistApi;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LoggerInterface::class, LogManager::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('vendor.pagination.bootstrap-4');
    }
}
