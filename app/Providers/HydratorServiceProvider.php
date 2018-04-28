<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HydratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\Dataset::hydrant(\App\Hydrants\DatasetHydrant::class);
        \App\Models\User::hydrant(\App\Hydrants\UserHydrant::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}