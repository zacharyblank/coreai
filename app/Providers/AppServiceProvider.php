<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(ResponseFactory $factory, User $user)
    {
        // \DB::listen(function ($query) {
        //     \Log::info($query->sql);
        //     \Log::info(print_r($query->bindings, true));
        // });

        Validator::extend('currentUserRequested', function($attribute, $value, $parameters, $validator) use ($user) {
            return $user->currentUser()->username == $value;
        });

        $factory->macro('error', function ($message, $status = 409) use ($factory) {
            return response()->json(['error' => $message], $status);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}