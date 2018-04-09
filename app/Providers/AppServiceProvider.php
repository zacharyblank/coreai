<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use App\Serializers\MetaArraySerializer;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(ResponseFactory $factory, Manager $fractal, User $user)
    {
        // \DB::listen(function ($query) {
        //     \Log::info($query->sql);
        //     \Log::info(print_r($query->bindings, true));
        // });

        $fractal->setSerializer(new MetaArraySerializer());

        Validator::extend('currentUserRequested', function($attribute, $value, $parameters, $validator) use ($user) {
            return $user->currentUser()->username == $value;
        });

        $factory->macro('collection', function ($data, $transformer, $status = 200, Array $meta = []) use ($factory, $fractal) {
            if (\Request::has('include')) {
                $fractal->parseIncludes(\Request::input('include'));
            }
            
            $response = array_merge($fractal->createData( new \League\Fractal\Resource\Collection($data, new $transformer) )->toArray(), $meta);

            if (getenv('APP_ENV') == "local" AND \Request::has('profile')) {
                return view('json', ['json' => json_encode($response)]);
            } else {
                return response()->json($response, $status);
            }
        });

        $factory->macro('item', function ($data, $transformer, $status = 200, Array $meta = []) use ($factory, $fractal) {
            if (\Request::has('include')) {
                $fractal->parseIncludes(\Request::input('include'));
            }

            $response = array_merge($fractal->createData( new \League\Fractal\Resource\Item($data, new $transformer) )->toArray(), $meta);

            if (getenv('APP_ENV') == "local" AND \Request::has('profile')) {
                return view('json', ['json' => json_encode($response)]);
            } else {
                return response()->json($response, $status);
            }
        });

        $factory->macro('data', function ($data, $status = 200, Array $meta = []) use ($factory, $fractal) {
            $response = $data;

            foreach ($data as $key => $resources) {
                $response[$key] = ($resources) ? $fractal->createData($resources)->toArray() : [];
            }

            return response()->json(['data' => $response], $status);
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