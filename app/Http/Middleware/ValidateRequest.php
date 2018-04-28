<?php

namespace App\Http\Middleware;

use Closure;
use Validator;
use Route;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ValidateRequest
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $method = $request->getMethod();

        if ($method == 'OPTIONS') {
            return $next($request);
        }

        $route = Route::getRoutes()->match($request);
        $action = $route->getActionName();

        if ($action === "Closure") {
            return $next($request);
        }

        $controller = '\\' . substr($action, 0, strpos($action, '@'));
        $method = substr($action, strpos($action, '@')+1, strlen($action));

        // Validate here!
        $input = array_merge($request->all(), $route->parameters());

        if ( ! isset($controller::$rules)) {
            return $next($request);
        }

        $validator = Validator::make($input, (array_key_exists($method, $controller::$rules) ? $controller::$rules[$method] : []), (property_exists($controller, 'messages') ? $controller::$messages : []));

        if (property_exists($controller, 'sometimes')) {
            if (array_key_exists($method, $controller::$sometimes)) {
                foreach ($controller::$sometimes[$method] as $key => $value) {
                    foreach ($value as $condition => $function) {
                        $validator->sometimes($key, $condition, $controller::$function());
                    }
                }
            }
        }

        if ($validator->fails()) {
            $messages = $validator->errors()->getMessages();
            $failed = $validator->failed();
            $result = [];

            foreach($failed as $input => $rules){
                $i = 0;
                foreach($rules as $rule => $ruleInfo){
                    $result[$input][strtolower($rule)] = $messages[$input][$i];
                    $i++;
                }
            }

            \Log::info("Request validation failed: \n" . print_r($result, true) . "\nRequest body: \n" . print_r($request->all(), true));

            return response()->error($result, 400);
        }

        return $next($request);
    }
}