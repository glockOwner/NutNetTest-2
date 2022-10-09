<?php

namespace App\Providers;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PerformerRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            RepositoryInterface::class,
            PerformerRepository::class
        );
    }
}
