<?php

namespace App\Providers;

use App\Hydrants\DatasetHydrant;
use App\Models\Dataset;
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
        Dataset::hydrant(DatasetHydrant::class);
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